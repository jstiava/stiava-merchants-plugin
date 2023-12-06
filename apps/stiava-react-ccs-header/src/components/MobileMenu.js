import React, { useState, useRef } from 'react';

import { useSpring, animated } from '@react-spring/web'

import MobileMenuItem from './MobileMenuItem'
import ChildMobileItem from './ChildMobileItem';
import SearchMobileMenuItem from './SearchMobileMenuItem';


function MobileMenu(props) {
  const [selected, setSelected] = useState(-1);
  const parentMenuRef = useRef(null);
  

  const [spring, api] = useSpring(
    () => ({
      width: "100%",
      color: "#000000"
    }),
    []
  )

  const [childrenSpring, childrenApi] = useSpring(
    () => ({
      left: "100vw"
    })
  )

  const handleClick = (key) => {
    setSelected(key);
    childrenApi.start({
      left: "0vw"
    })
  }

  const handleBack = () => {
    childrenApi.start({
      left: "100vw"
    })
    if (parentMenuRef && parentMenuRef.current) {
      console.log(parentMenuRef.current.children[1].children[0])
      parentMenuRef.current.children[1].children[0].focus();
    }
    setSelected(-1);
  }

  const openSearch = () => {
    return;
  }

  return (
    <>
      <div ref={parentMenuRef} className='header-mobile-main-menu'>
        <SearchMobileMenuItem key={"search_mobile"} onClick={() => { openSearch() }} />
        {Object.keys(props.context).map((key, index) => {
          const item = props.context[key];
          return (
            <MobileMenuItem key={key} index={key} selected={selected} spring={spring} onClick={() => { handleClick(key) }} {...item} />
          );
        })}
      </div>

      <animated.div className='header-menu-side-children mobile' style={childrenSpring}>
        {props.context[selected]?.children && (
          <>
            <MobileMenuItem isBack title={props.context[selected].title} spring={spring} onClick={() => { handleBack() }} />
            {
              Object.keys(props.context[selected].children).map((key, index) => {
                const child = props.context[selected].children[key];
                return (<ChildMobileItem key={index} {...child} />);
              })
            }
          </>
        )}
      </animated.div>
    </>
  )
};


export default MobileMenu;
