import React from 'react';
import { Button, Popover, Box, ButtonBase } from '@mui/material';
import { styled } from '@mui/material/styles';
import LinkIcon from '@mui/icons-material/Link';

function ChildMobileItem({ ...props }) {

  const ChildMobileContainedButton = styled(Button)(() => ({
    position: 'relative',
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
    padding: '1.5rem 2rem',
    width: '100%',
    height: 'fit-content',
    textAlign: 'left',
    textTransform: 'none',
    color: "#000000"
  }));

  console.log(props);
  
  return (
    <div className='mobile-menu-child-item'>
      <h6>{props.title}</h6>
      {props.children && (
        Object.keys(props.children).map((key) => {
          const child = props.children[key];
          {console.log(child)}
          return (
            <ChildMobileContainedButton>
              <LinkIcon />
              <div className='mobile-menu-child-item-content'>
                <h4>{child.title}</h4>
                <p>{child.content}</p>
              </div>
            </ChildMobileContainedButton>
          );
        })
      )}
    </div>
  )
};


export default ChildMobileItem;
