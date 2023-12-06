import React, { useState } from 'react';
import { ReactComponent as CCSLogo } from './resources/WashU_CCS_White_2Line.svg';
import { useSpring, animated, easings } from '@react-spring/web'

import MainMenu from './components/MainMenu';
import QuickItem from './components/QuickItem';
import SupportIcon from './resources/support';
import { IconButton } from '@mui/material';
import SearchIcon from '@mui/icons-material/Search';


import { Squash as Hamburger } from 'hamburger-react'

const contact = {
  "id": "_40",
  "title": "Contact Us",
  "url": "http:\/\/cardtest.local\/washu-id-card\/",
  "classes": [""],
  "children": []
}

const signIn = {
  "id": "_90",
  "title": "Sign In",
  "url": "http:\/\/cardtest.local\/admin\/",
  "classes": [""],
  "children": []
}

const handleGoHome = () => {
  window.location.href = 'http://localhost:3001';
}


function Header(props) {

  return (
    <>
      <div id="header-main">
        <CCSLogo onClick={handleGoHome} />
        <div className='header-main-right'>
          <QuickItem item={contact} filled><SupportIcon /></QuickItem>
          <QuickItem item={signIn} />
        </div>
      </div>
      <div id="header-menu">
        <MainMenu {...props} />
        <IconButton>
          <SearchIcon color='#000000' />
        </IconButton>
      </div>
    </>
  )
};


export default Header;
