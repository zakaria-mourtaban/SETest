#!/usr/bin/env python3
"""
Calculate total 42 intra login (session) time over the past 2 weeks.

Uses the official 42 API (https://api.intra.42.fr) via OAuth2 client-credentials.
No password required: create an app at
https://profile.intra.42.fr/oauth/applications/new and set the UID/SECRET below
(or via the FT_CLIENT_ID / FT_CLIENT_SECRET environment variables).

Usage:
    FT_CLIENT_ID=xxx FT_CLIENT_SECRET=yyy python3 login_time.py zmourtab
"""
import os
import sys
import time
from datetime import datetime, timedelta, timezone
from urllib.request import Request, urlopen
from urllib.parse import urlencode
import json

API = "https://api.intra.42.fr"

# Fill these in, or pass them as environment variables.
CLIENT_ID = os.environ.get("FT_CLIENT_ID", "PUT_YOUR_UID_HERE")
CLIENT_SECRET = os.environ.get("FT_CLIENT_SECRET", "PUT_YOUR_SECRET_HERE")


def get_token():
    data = urlencode({
        "grant_type": "client_credentials",
        "client_id": CLIENT_ID,
        "client_secret": CLIENT_SECRET,
    }).encode()
    req = Request(f"{API}/oauth/token", data=data, method="POST")
    with urlopen(req) as r:
        return json.load(r)["access_token"]


def api_get(path, token, params=None):
    url = f"{API}{path}"
    if params:
        url += "?" + urlencode(params)
    req = Request(url, headers={"Authorization": f"Bearer {token}"})
    # Simple backoff for the API's rate limit (2 req/s, 1200/hour).
    for attempt in range(5):
        try:
            with urlopen(req) as r:
                return json.load(r)
        except Exception as e:
            if attempt == 4:
                raise
            time.sleep(2 ** attempt)


def main():
    login = sys.argv[1] if len(sys.argv) > 1 else "zmourtab"
    if "PUT_YOUR" in CLIENT_ID or "PUT_YOUR" in CLIENT_SECRET:
        sys.exit("Set FT_CLIENT_ID and FT_CLIENT_SECRET (see header of this file).")

    token = get_token()
    now = datetime.now(timezone.utc)
    since = now - timedelta(days=14)

    # Pull locations (sessions) page by page until we pass the 2-week window.
    total = timedelta()
    sessions = []
    page = 1
    while True:
        locs = api_get(f"/v2/users/{login}/locations", token,
                       {"page[size]": 100, "page[number]": page})
        if not locs:
            break
        stop = False
        for loc in locs:
            begin = datetime.fromisoformat(loc["begin_at"].replace("Z", "+00:00"))
            if begin < since:
                stop = True
                continue
            # end_at is null for an active (ongoing) session.
            end = (datetime.fromisoformat(loc["end_at"].replace("Z", "+00:00"))
                   if loc.get("end_at") else now)
            dur = end - begin
            total += dur
            sessions.append((begin, end, dur, loc.get("host", "")))
        if stop or len(locs) < 100:
            break
        page += 1

    sessions.sort()
    print(f"Login time for '{login}' — {since.date()} to {now.date()}\n")
    for begin, end, dur, host in sessions:
        h, rem = divmod(int(dur.total_seconds()), 3600)
        m = rem // 60
        print(f"  {begin:%Y-%m-%d %H:%M} -> {end:%H:%M}  {h:>3}h{m:02d}m  {host}")

    secs = int(total.total_seconds())
    print(f"\nSessions: {len(sessions)}")
    print(f"TOTAL: {secs // 3600}h {(secs % 3600) // 60}m "
          f"({total.total_seconds() / 3600:.1f} hours over 14 days, "
          f"avg {total.total_seconds() / 3600 / 14:.1f}h/day)")


if __name__ == "__main__":
    main()
