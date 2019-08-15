import React, { Component } from 'react'
import { View, Text, TouchableOpacity, StyleSheet } from 'react-native';
import Sound from 'react-native-sound';

const styles = StyleSheet.create({
  container: {
    justifyContent: 'center',
    alignItems: 'center',
  },
  title: {
    textAlign: 'center',
    marginVertical: 40,
    fontSize: 24,
    width: 900,
  },
  playButton: {
    borderWidth: 5,
    borderColor: 'rgba(0,0,0,0.8)',
    alignItems: 'center',
    justifyContent: 'center',
    width: 100,
    height: 100,
    backgroundColor: 'green',
    borderRadius: 100,
  },
  stopButton: {
    borderWidth: 5,
    borderColor: 'rgba(0,0,0,0.8)',
    alignItems: 'center',
    justifyContent: 'center',
    width: 100,
    height: 100,
    backgroundColor: 'red',
    borderRadius: 100,
  },
});

export default class Main extends Component {
  constructor(props) {
    super(props);
    this.state = {
      sound: null,
    }
  }

  playCrabSound = () => {
    const { sound } = this.state;
    // Play the sound with an onEnd callback
    sound.play((success) => {
      if (success) {
        console.log('successfully finished playing');
      } else {
        console.log('playback failed due to audio decoding errors');
        // reset the player to its uninitialized state (android only)
        // this is the only option to recover after an error occured and use the player again
        sound.reset();
      }
    });
  }

  stopCrabSound = () => {
    const { sound } = this.state;
    sound.pause();
  }

  componentDidMount() {
    var crab = new Sound('crab.mp3', Sound.MAIN_BUNDLE, (error) => {
      if (error) {
        console.log('failed to load the sound', error);
        return;
      }
      // loaded successfully
      console.log('duration in seconds: ' + crab.getDuration() + 'number of channels: ' + crab.getNumberOfChannels());
    });
    this.setState(prevState => ({
      ...prevState,
      sound: crab,
    }));
  }

  render() {
    return (
      <View style={styles.container}>
        <Text style={styles.title}>Presiona el botoncito</Text>
        <TouchableOpacity style={styles.playButton} onPress={() => this.playCrabSound()}>
          <Text>CANGREJO</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.stopButton} onPress={() => this.stopCrabSound()}>
          <Text>DETENER</Text>
          <Text>CANGREJO</Text>
        </TouchableOpacity>
      </View>
    )
  }
}
