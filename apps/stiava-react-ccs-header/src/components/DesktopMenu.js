import React, { useState } from 'react';

import { useSpring, animated, easings } from '@react-spring/web'


import MobileMenuItem from './MobileMenuItem'
import ChildMobileItem from './ChildMobileItem';
import SearchMobileMenuItem from './SearchMobileMenuItem';

function DesktopMenu(props) {
  const [selected, setSelected] = useState(-1);

  const [spring, api] = useSpring(
    () => ({
      width: "100%",
      color: "#000000"
    }),
    []
  )

  const handleClick = (key) => {
    setSelected(key);
    api.start({
      width: "40%"
    })
  }


  const openSearch = () => {
    return;
  }


  return (
    <>
      <div className='header-mobile-main-menu'>
      <SearchMobileMenuItem onClick={() => { openSearch() }} />
        {Object.keys(props.context).map((key) => {
          const item = props.context[key];
          return (
            <MobileMenuItem key={key} index={key} selected={selected} spring={spring} onClick={() => { handleClick(key) }} {...item} />
          );
        })}
      </div>
      {selected !== -1 && props.context[selected].children && (
        <div className='header-menu-side-children tablet'>
          {
            props.context[selected].children.map((child, index) => (
              <ChildMobileItem key={index} {...child}/>
            ))
          }
        </div>
      )}
    </>
  )
};


export default DesktopMenu;
