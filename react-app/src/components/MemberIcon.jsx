const MemberIcon = ({ user }) => {
  const { name, color } = user;

  return (
    <div className={`member-icon ${color}`}>
      <p>{name[0].toUpperCase()}</p>
    </div>
  );
};

export default MemberIcon;
