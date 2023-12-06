import React, { useMemo, useState } from 'react';
import { ThemeProvider, createTheme } from '@mui/material/styles';
import Header from './Header';
import MobileHeader from './MobileHeader';
import TabletHeader from './TabletHeader';
import { useEffect } from 'react';
import menu from './menu.json';

function App() {

  // const context = window.reactAdminContext;


  const [screenSize, setScreenSize] = useState('');
  const [mode, setMode] = useState('light');
  const theme = useMemo(() => createTheme(design(mode)), [mode]);

  const handleResize = () => {
    const mediaQueryListDesktop = window.matchMedia("(min-width: 1060px)");
    const mediaQueryListTablet = window.matchMedia("(min-width: 768px) and (max-width: 1059px)");
    const mediaQueryListMobile = window.matchMedia("(max-width: 767px)");

    if (mediaQueryListDesktop.matches) {
      setScreenSize('desktop');
    } else if (mediaQueryListTablet.matches) {
      setScreenSize('tablet');
    } else if (mediaQueryListMobile.matches) {
      setScreenSize('mobile');
    }
  }

  useEffect(() => {

    handleResize();

    window.addEventListener('resize', handleResize);

    return () => {
      window.removeEventListener('resize', handleResize);
    };

  }, []);



  return (
    <ThemeProvider theme={theme}>
      {screenSize === 'desktop' && (
        <Header context={menu} />
      )}
      {screenSize === 'tablet' && (
        <TabletHeader context={menu} />
      )}
      {screenSize === 'mobile' && (
        <MobileHeader context={menu} />
      )}
    </ThemeProvider>
  );
}


const design = (mode) => ({
  palette: {
    mode,
    ...(mode === 'light'
      ? {
        // palette values for light mode
        primary: {
          main: '#a51417',
          contrastText: '#ffffff'
        },
        secondary: {
          main: '#3d3d3d',
          contrastText: '#ffffff'
        },
        warning: {
          main: "#ed6c02",
          light: "#ff9800",
          dark: "#e65100",
          contrastText: "#fff"
        },
        error: {
          main: "#410002",
          light: "#ef5350",
          dark: "#c62828",
          contrastText: '#410002'
        }
      }
      : {
        // palette values for dark mode
        primary: {
          main: '#ffb4ab',
          contrastText: '#000000'
        },
        secondary: {
          main: '#3d3d3d',
          contrastText: '#ffffff'
        },
        error: {
          main: "#93000a",
          dark: "#93000a",
          contrastText: "#ffdad6",
        }
      })
  },
  typography: {
    fontFamily: [
      'Source Sans Pro',
      '-apple-system',
      'BlinkMacSystemFont',
      '"Segoe UI"',
      'Roboto',
      '"Helvetica Neue"',
      'Arial',
      'sans-serif',
      '"Apple Color Emoji"',
      '"Segoe UI Emoji"',
      '"Segoe UI Symbol"',
    ].join(',')
  }
});

export default App;
