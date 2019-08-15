// @flow
import React from 'react';
import {
  Header, Left, Right, Body, Text, Title, Subtitle
} from 'native-base';

type Props = {
  title: String,
  subtitle: String,
  left: Object,
  right: Object
}

const CommonHeader = (props: Props) => {
  const { title, subtitle, left, right } = props;

  return (
    <Header>
      <Left />
      <Body>
        {title && <Title>{title}</Title>}
        {subtitle && <Subtitle>{subtitle}</Subtitle>}
      </Body>
      <Right />
    </Header>
  );
}

export default CommonHeader;
