# Fix the bugs in order to be able to run the project. (You can fix the backend and finish apis then jump to frontend or vice versa, it's your call)

### Laravel

1. Create migrations for a database having two tables: users and projects (A user can have many projects and a project can have many members)
2. Create all CRUD opertations for users and projects
3. The users table should have a "requests_num" column, tracking the number of APIs called by each user. Implement a middleware that updates this column on each request. (You can use the user_id since JWT implementation is not required)

### React

1. You cannot change any file in the components and utils directory
2. Continue the implementation of the projects page using projectsContext
