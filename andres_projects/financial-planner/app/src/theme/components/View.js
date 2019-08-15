import variable from '../variables';

export default (variables = variable) => {
  const viewTheme = {
    '.padder': {
      padding: variables.contentPadding,
    },
  };

  return viewTheme;
};
