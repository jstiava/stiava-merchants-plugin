import React, {useEffect, useState} from 'react';
import { useSpring, animated, easings } from '@react-spring/web'

import MenuItem from './MenuItem'
import DesktopMenu from './DesktopMenu';

function MainMenu(props) {

  const [isOpen, setIsOpen] = useState(false);

  const [spring, api] = useSpring(
    () => ({
      opacity: 1
    }),
    []
  )

  const handleHover = (item) => {
    console.log(item)
  }

  
  return (
    <>
      <div className='header-main-menu-left'>
        {Object.keys(props.context).map((key) => {
          const item = props.context[key];
          return (
            <MenuItem {...item} onHover={() => {handleHover(item)}} />
          );
        })}
      </div>
      {isOpen && (
        <div style={spring} id="header-mobile-menu" className='desktop'>
          <DesktopMenu {...props} />
        </div>
      )}
    </>
  )
};


export default MainMenu;
