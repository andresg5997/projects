import React from 'react';
import { StyleProvider } from 'native-base';
import variables from './variables';
import getTheme from './components';

function Theme(props) {
  return (
    <StyleProvider style={ getTheme(variables) }>
      {props.children}
    </StyleProvider>
  );
}

export default Theme;
