import React from 'react';
import { Button, Popover, Box } from '@mui/material';

import SupportIcon from '../resources/support';

function QuickItem(props) {

  const item = {...props.item};

  const styling = props.filled ? {
    color: "#a51417", backgroundColor: "#ffffff"
  } : {
    color: "#ffffff", backgroundColor: "#a5141700"
  };

  return (
    <div className='quick-item'>
      <Button className='header-menu-item-parent' key={item.id} 
      style={styling}>{props.children}{item.title}</Button>
    </div>
  )
};


export default QuickItem;
