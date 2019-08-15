// @flow
import React from 'react';
import { View, Modal } from 'react-native';
import { Content } from 'native-base';
// styles
import cs from '../theme/common-styles';

type Props = {
  animationType?: String,
  modalIsOpen: Boolean,
  toggleModal?: Function,
  children: React.Node,
}

const CommonModal = (props: Props) => {
  const { animationType, modalIsOpen, toggleModal, children } = props;

  return (
    <Modal
      animationType={animationType}
      transparent
      visible={modalIsOpen}
      onRequestClose={() => toggleModal()}
    >
      <View style={cs.modalContainer}>
        <View style={cs.modal}>
          <Content>
            { children }
          </Content>
        </View>
      </View>
    </Modal>
  );
};

CommonModal.defaultProps = {
  animationType: 'fade',
  toggleModal: {},
};

export default CommonModal;
