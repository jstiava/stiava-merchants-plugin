import React, { useState } from 'react';
import { Button, Drawer, FormControl, OutlinedInput, InputLabel, TextField, InputAdornment } from '@mui/material';
import KeyboardArrowLeftIcon from '@mui/icons-material/KeyboardArrowLeft';
import KeyboardArrowRightIcon from '@mui/icons-material/KeyboardArrowRight';
import SearchIcon from '@mui/icons-material/Search';
import ArrowOutwardIcon from '@mui/icons-material/ArrowOutward';
import { useSpring, animated } from '@react-spring/web'

function SearchMobileMenuItem({ onClick, ...item }) {

  const [open, setOpen] = useState(false);

  const handleClick = () => {
    return;
  }

  return (
    <>
      <div className='header-mobile-menu-item' style={{height: '7rem', backgroundColor: "#f1f1f1"}}>
        <div className='header-menu-item-parent mobile search' onClick={handleClick}>
          <div>
            <FormControl sx={{ m: 1, width: '100%' }} variant='outlined'>
              <InputLabel sx={{height: '4rem'}} htmlFor="outlined-adornment-search" ></InputLabel>
              <OutlinedInput
                id="outlined-adornment-search"
                startAdornment={
                  <InputAdornment position="start">
                    <SearchIcon />
                  </InputAdornment>
                }
                endAdornment={
                  <InputAdornment position="end">
                    <Button>Search</Button>
                  </InputAdornment>
                }
              />
            </FormControl>
          </div>
        </div>
      </div>
    </>
  )
};


export default SearchMobileMenuItem;
