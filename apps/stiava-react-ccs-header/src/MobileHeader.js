import React, { useState } from 'react';
import { ReactComponent as CCSLogo } from './resources/WashU_CCS_White_2Line.svg';
import { Button } from '@mui/material';
import MobileMenu from './components/MobileMenu';
import { useSpring, animated, easings } from '@react-spring/web'

import { Squash as Hamburger } from 'hamburger-react';

const contact = {
  "id": "_40",
  "title": "Contact Us",
  "url": "http:\/\/cardtest.local\/washu-id-card\/",
  "classes": [""],
  "children": []
}




function MobileHeader(props) {
  const [anchorEl, setAnchorEl] = React.useState(null);
  const open = Boolean(anchorEl);
  const handleClick = (event) => {
    setAnchorEl(event.currentTarget);
  };
  const handleClose = () => {
    setAnchorEl(null);
  };

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

  return (
    <>
      <div id="header-main" className='mobile'>
        <CCSLogo />
        <div className='header-main-right'>
          <Button sx={{p: 0, m: 0, width: '48px', minWidth: '48px'}} onClick={handleOpen}>
            <Hamburger toggled={isOpen} color='#ffffff' size={24} />
          </Button>
        </div>
      </div>
      {isOpen && (
        <div id="header-mobile-menu" style={spring} className='mobile'>
          <MobileMenu {...props} />
        </div>
      )}
    </>
  )
};


export default MobileHeader;
