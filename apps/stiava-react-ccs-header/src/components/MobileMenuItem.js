import React, { useState, useRef, useEffect } from 'react';
import { Button, Drawer } from '@mui/material';
import KeyboardArrowLeftIcon from '@mui/icons-material/KeyboardArrowLeft';
import KeyboardArrowRightIcon from '@mui/icons-material/KeyboardArrowRight';
import ArrowOutwardIcon from '@mui/icons-material/ArrowOutward';
import { useSpring, animated } from '@react-spring/web'

function MobileMenuItem({ clickRef, index, selected, spring, onClick, ...item }) {

  const [open, setOpen] = useState(false);
  const mobileMenuRef = useRef(null);

  const handleClick = () => {
    if (!item.children) {
      window.location.href = item.url;
      return;
    }
    onClick();
  }

  useEffect(() => {
    console.log(mobileMenuRef)
    if (mobileMenuRef && mobileMenuRef.current) {
      mobileMenuRef.current.focus();
    }
  }, [selected])

  if (item.isBack) {
    return (
      <>
        <div className='header-mobile-menu-item'>
          <Button ref={mobileMenuRef} className='header-menu-item-parent mobile' onClick={onClick}>
            <div>
              <KeyboardArrowLeftIcon />
              {"Back"}
            </div>
          </Button>
        </div>
      </>
    )
  }

  return (
    <>
      <div className='header-mobile-menu-item' 
        style={selected === -1 ? {backgroundColor: "#ffffff", opacity: 1} : selected === index ? { opacity: "100%", backgroundColor: "#f1f1f1" } : { opacity: "40%" }}
      >
        <Button className='header-menu-item-parent mobile' onClick={handleClick}>
          <animated.div style={spring} >
            {item.title}
            {item.children ? (
              <KeyboardArrowRightIcon />
            ) : (
              <ArrowOutwardIcon fontSize='small' />
            )}
          </animated.div>
        </Button>
      </div>
    </>
  )
};


export default MobileMenuItem;
