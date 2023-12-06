import React from 'react';
import { Button, Popover, Box } from '@mui/material';

import SupportIcon from '../resources/support';

function FooterQuickItem(props) {

  const item = {...props.item};

  const styling = {
    color: "#ffffff", backgroundColor: "#a5141700"
  };

  return (
    <div className='quick-item'>
      <Button disableFocusRipple sx={{
        fontWeight: 400, 
        textDecorationLine: 'underline',
        textTransform: 'unset',
        fontSize: '1rem'
      }} className='footer-menu-item-parent' key={item.id} 
      style={styling}>{props.children}{item.title}</Button>
    </div>
  )
};


export default FooterQuickItem;
