import React, { createContext, useContext, useState, useEffect } from "react";
import apiRoutes from "../utils/api";

const ProjectsContext = createContext();

export const useProjects = () => {
	return useContext(ProjectsContext);
};

function getRandomColor() {
	const colors = ["red", "blue", "green", "yellow", "orange", "purple"];
	const randomColor = colors[Math.floor(Math.random() * colors.length)];
	return randomColor;
}

export const ProjectsProvider = ({ children }) => {
	const [projects, setProjects] = useState([]);
	const [loading, setLoading] = useState(true);
	const [error, setError] = useState(null);

	useEffect(() => {
		const fetchProjects = async () => {
			try {
				const response = await fetch(apiRoutes.getProjects);
				if (!response.ok) {
					throw new Error("Failed to fetch projects");
				}
				const data = await response.json();

				const projectsNew = data.map((project) => ({
					...project,
					members: project.members.map((member) => ({
						...member,
						color: getRandomColor(),
					})),
				}));

				setProjects(projectsNew);
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
