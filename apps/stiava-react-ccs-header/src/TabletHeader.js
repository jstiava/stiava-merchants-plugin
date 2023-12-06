import React, { useState } from 'react';
import { ReactComponent as CCSLogo } from './resources/WashU_CCS_White_2Line.svg';
import { useSpring, animated, easings } from '@react-spring/web'

import MainMenu from './components/MainMenu';
import QuickItem from './components/QuickItem';
import SupportIcon from './resources/support';
import { IconButton, Button } from '@mui/material';
import SearchIcon from '@mui/icons-material/Search';
import TabletMenu from './components/TabletMenu'

import { Squash as Hamburger } from 'hamburger-react';

const contact = {
  "id": "_40",
  "title": "Contact Us",
  "url": "http:\/\/cardtest.local\/washu-id-card\/",
  "classes": [""],
  "children": []
}




function TabletHeader(props) {

  const [isOpen, setIsOpen] = useState(false);

  const [spring, api] = useSpring(
    () => ({
      opacity: 1
    }),
    []
  )

  const handleOpen = () => {
    if (isOpen) {
      api.start({
        opacity: 0
      })
      setTimeout(() => {
        setIsOpen((prev) => !prev);
      }, 50);
      return;
    }  
    setIsOpen((prev) => !prev)
  }
  
  const [anchorEl, setAnchorEl] = React.useState(null);
  const open = Boolean(anchorEl);
  const handleClick = (event) => {
    setAnchorEl(event.currentTarget);
  };
  const handleClose = () => {
    setAnchorEl(null);
  };

  return (
    <>
      <div id="header-main" className='tablet'>
        <CCSLogo />
        <div className='header-main-right'>
          <Button sx={{ p: 0, m: 0, width: 'fit-content' }} onClick={handleOpen}>
            <Hamburger toggled={isOpen} color='#ffffff' size={24} />
          </Button>
        </div>
      </div>
      {isOpen && (
        <div style={spring} id="header-mobile-menu" className='tablet'>
          <TabletMenu {...props} />
        </div>
      )}
    </>
  )
};


export default TabletHeader;
