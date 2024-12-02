import React, { createContext, useContext, useState, useEffect } from 'react';
import apiRoutes from './apis'; // Import the apiRoutes object

// Create a context for the projects data
const ProjectsContext = createContext();

// Create a custom hook to use the projects context
export const useProjects = () => {
  return useContext(ProjectsContext);
};

// Create a provider component to wrap your app and provide the context value
export const ProjectsProvider = ({ children }) => {
  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchProjects = async () => {
      try {
        const response = await fetch(apiRoutes.getProjects);
        if (!response.ok) {
          throw new Error('Failed to fetch projects');
        }
        const data = await response.json();
        setProjects(data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchProjects();
  }, []);

  return (
    <ProjectsContext.Provider value={{ projects, loading, error }}>
      {children}
    </ProjectsContext.Provider>
  );
};
