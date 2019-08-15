import variable from '../variables';

export default (variables = variable) => {
  const textTheme = {
    fontSize: variables.DefaultFontSize,
    fontFamily: variables.fontFamily,
    color: variables.defaultTextColor,
    '.note': {
      color: '#9E9E9E',
      fontSize: variables.noteFontSize,
    }
  };

  return textTheme;
};
