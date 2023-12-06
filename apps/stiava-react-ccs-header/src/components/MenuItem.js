import React from 'react';
import { Button } from '@mui/material';

function MainItem(item) {

  const handleClick = () => {
    console.log(item)
  }

  return (
    <div className='header-menu-item'>
      <Button 
        className='header-menu-item-parent' 
        onClick={handleClick} 
        onMouseEnter={item.onHover}
        key={item.id} 
        style={{ color: "#000000" }}
      >
        {item.title}
      </Button>
    </div>
  )
};


export default MainItem;
