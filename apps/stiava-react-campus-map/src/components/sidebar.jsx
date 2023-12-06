import React, { useState, useEffect, createRef, useRef } from 'react';
import { Drawer } from '@mui/material';

/**
 * 
 * @param {*} merchant 
 * @param {*} dateTime 
 * @param {*} ...props
 * @returns 
 */
const TargetSidebar = ({ selected, unClick }) => {

  const [drawer, setDrawer] = useState(false);

  useEffect(() => {
    if (selected.render) {
      setDrawer(true);
    }
  }, [selected])

  const toggleDrawer = (open) => (event) => {
    if (event.type === 'keydown' && (event.key === 'Tab' || event.key === 'Shift')) {
      return;
    }

    if (open === false) {
      unClick();
    }

    setDrawer(open);
  };


  return (
    <Drawer
      className='drawer'
      anchor={selected.side}
      open={drawer}
      onClose={toggleDrawer(false)}
      PaperProps={{
        sx: {
          height: '100%',
          width: '100%',
          maxWidth: '500px',
          p: '4rem'
        },
      }}
    >
      <p>{selected.element?.id}</p>
    </Drawer>
  )
}

export default TargetSidebar;