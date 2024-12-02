import React from "react";
import Project from "../components/Project";
import { ProjectsProvider, useProjects } from "../context/projectsContext";

const Projects = () => {
  const { projects, loading, error } = useProjects();

  if (loading) {
    return <div>Loading...</div>;
  }

  if (error) {
    return <div>Error: {error}</div>;
  }

  return (
    <div className="projects-container">
      {projects.map((p) => (
        <Project project={p} key={p.id} />
      ))}
    </div>
  );
};

const ProjectsPage = () => {
  return (
    <ProjectsProvider>
      <Projects />
    </ProjectsProvider>
  );
};

export default ProjectsPage;
