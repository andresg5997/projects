// @flow
import React from 'react';
import {
  ActivityIndicator,
  Modal,
  View,
} from 'react-native';
// styles
import variables from '../theme/variables';
import cs from '../theme/common-styles';

type Props = {
  isLoading: Boolean
}

const Loading = (props: Props) => {
  const { isLoading } = props;
  return (
    <Modal
      animationType="fade"
      transparent
      visible={isLoading}
      onRequestClose={() => { }}
    >
      <View style={cs.modalContainer}>
        <View style={cs.modal}>
          <ActivityIndicator size="large" color={variables.brandPrimary} />
        </View>
      </View>
    </Modal>
  );
};

export default Loading;
