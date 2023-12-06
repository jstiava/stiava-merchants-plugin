import React, { useState, useEffect } from 'react';
import {ReactComponent as DanforthCampusMap} from '../resources/DanforthCampusMap.svg';
import TargetSidebar from './sidebar';

const Map = () => {

  const [target, setTarget] = useState({
    render: false,
    side: 'right',
    element: null
  });

  const unClick = () => {
    target.element.style.fill = '#6F7977';
  }

  useEffect(() => {
    const buildingDiv = document.getElementById('Building');
    const screenWidth = window.innerWidth; // Get the screen width

    if (buildingDiv) {
      const buildingChildren = buildingDiv.children;

      // Define the onClick handler function
      const handleClick = (event) => {
        // Handle the click event here
        const clickX = event.clientX; // Get the horizontal click coordinate
        setTarget(() => ({
          render: true,
          side: clickX < screenWidth / 2 ? 'right' : 'left',
          element: event.currentTarget
        }))
        event.currentTarget.style.fill = '#ffffff';
        if (event.target != event.currentTarget) {
          event.stopPropagation();
        }

      };

      // Add onClick event to all children of the "Building" div
      for (let i = 0; i < buildingChildren.length; i++) {
        const child = buildingChildren[i];
        child.addEventListener('click', handleClick);
        child.style.transition = 'fill 0.25s';
      }

    }
  }, []);

  return (
    <>
      <DanforthCampusMap />
      <TargetSidebar selected={target} unClick={unClick} />
    </>
  );
};

export default Map;