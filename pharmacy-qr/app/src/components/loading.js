import React from 'react';
import { ActivityIndicator, StyleSheet, Text, View } from 'react-native';
import { colors } from '../theme';

const styles = StyleSheet.create({
  loadingView: {
    alignItems: 'center',
    justifyContent: 'center',
    flex: 1,
  },
  loadingText: {
    color: colors.mainDark,
    fontSize: 14,
    fontWeight: 'bold',
  },
});

const Loading = () => (
  <View style={styles.loadingView}>
    <ActivityIndicator size="large" color={colors.mainDark} />
    <Text style={styles.loadingText}>Loading . . .</Text>
  </View>
);

export default Loading;
