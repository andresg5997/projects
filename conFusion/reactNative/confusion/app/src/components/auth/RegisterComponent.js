import React, { Component } from 'react';
import { View, StyleSheet, ScrollView, Image, Alert } from 'react-native';
import { Input, Icon, Button, CheckBox } from 'react-native-elements';
import ImagePicker from 'react-native-image-picker';
import ImageCropper from 'react-native-image-crop-picker';
import SecureStorage from 'react-native-secure-storage';

// The following modules are being used because the project
// is ejected and doesn't rely on the expo SDK:
// react-native-secure-storage
// react-native-image-picker
// react-native-image-crop-picker

// This last module (react-native-image-crop-picker) is the
// one in charge of formatting the image in the right way

// This module is separated from the LoginComponent and both
// are joined into a bottomTabNavigator in a different file

const styles = StyleSheet.create({
  container: {
    justifyContent: 'center',
    margin: 20,
  },
  imageContainer: {
    flex: 1,
    flexDirection: 'row',
    margin: 20,
    justifyContent: 'space-between',
  },
  image: {
    margin: 10,
    width: 80,
    height: 60,
  },
  formInput: {
    margin: 20,
  },
  formCheckbox: {
    margin: 20,
    backgroundColor: null,
  },
  formButton: {
    margin: 60,
  },
});

class RegisterTab extends Component {
  constructor(props) {
    super(props);

    this.state = {
      username: '',
      password: '',
      firstname: '',
      lastname: '',
      email: '',
      remember: false,
      imageUrl: '',
    };
  }

  static navigationOptions = {
    title: 'Register',
    tabBarIcon: ({ tintColor }) => (
      <Icon
        name="user-plus"
        type="font-awesome"
        size={24}
        iconStyle={{ color: tintColor }}
      />
    ),
  };

  handleRegister = () => {
    const { username, password, firstname, lastname, email, remember, imageUrl } = this.state;
    if (remember) {
      const userdata = JSON.stringify({ username, password, firstname, lastname, email, imageUrl });
      SecureStorage.setItem('registeruserinfo', userdata);
    } else {
      SecureStorage.removeItem('registeruserinfo');
    }
  }

  getUserInfo = async () => {
    const userdataJSON = await SecureStorage.getItem('registeruserinfo');
    if (userdataJSON) {
      const userdata = JSON.parse(userdataJSON);
      this.setState(prevState => ({
        ...prevState,
        username: userdata.username,
        password: userdata.password,
        firstname: userdata.firstname,
        lastname: userdata.lastname,
        email: userdata.email,
        imageUrl: userdata.imageUrl,
      }));
    }
  }

  getImageFromCamera = async () => {
    const options = {
      title: 'Select Avatar',
      noData: true,
      mediaType: 'photo',
    };
    ImagePicker.launchCamera(options, (response) => {
      if (!response.didCancel) {
        if (response.error) {
          Alert.alert(
            'ERROR',
            response.error,
            [
              { text: 'OK' },
            ],
          );
        } else {
          this.editImage(response.uri);
        }
      }
    });
  }

  getImageFromGallery = async () => {
    const options = {
      title: 'Select Avatar',
      noData: true,
      mediaType: 'photo',
    };
    ImagePicker.launchImageLibrary(options, (response) => {
      if (!response.didCancel) {
        if (response.error) {
          Alert.alert(
            'ERROR',
            response.error,
            [
              { text: 'OK' },
            ],
          );
        } else {
          this.editImage(response.uri);
        }
      }
    });
  }

  editImage = (uri) => {
    ImageCropper.openCropper({
      width: 400,
      height: 300,
      path: uri,
    })
      .then((image) => {
        this.setState(prevState => ({
          ...prevState,
          imageUrl: image.path,
        }));
      })
      .catch(() => console.log('Cancelled'));
  }

  componentDidMount() {
    this.getUserInfo();
  }

  render() {
    const { username, password, firstname, lastname, email, remember, imageUrl } = this.state;
    return (
      <ScrollView>
        <View style={styles.container}>
          <View style={styles.imageContainer}>
            <Image
              source={imageUrl === '' ? require('../../../images/logo.png') : { uri: imageUrl }}
              style={styles.image}
            />
            <Button title="Camera" onPress={() => this.getImageFromCamera()} />
            <Button title="Gallery" onPress={() => this.getImageFromGallery()} />
          </View>
          <Input
            placeholder="Username"
            leftIcon={{ type: 'font-awesome', name: 'user-o' }}
            onChangeText={text => this.setState(prevState => ({ ...prevState, username: text }))}
            value={username}
            containerStyle={styles.formInput}
          />
          <Input
            placeholder="Password"
            leftIcon={{ type: 'font-awesome', name: 'key' }}
            onChangeText={text => this.setState(prevState => ({ ...prevState, password: text }))}
            value={password}
            containerStyle={styles.formInput}
          />
          <Input
            placeholder="First Name"
            leftIcon={{ type: 'font-awesome', name: 'user-o' }}
            onChangeText={text => this.setState(prevState => ({ ...prevState, firstname: text }))}
            value={firstname}
            containerStyle={styles.formInput}
          />
          <Input
            placeholder="Last Name"
            leftIcon={{ type: 'font-awesome', name: 'user-o' }}
            onChangeText={text => this.setState(prevState => ({ ...prevState, lastname: text }))}
            value={lastname}
            containerStyle={styles.formInput}
          />
          <Input
            placeholder="Email"
            leftIcon={{ type: 'font-awesome', name: 'envelope-o' }}
            onChangeText={text => this.setState(prevState => ({ ...prevState, email: text }))}
            value={email}
            containerStyle={styles.formInput}
          />
          <CheckBox
            title="Remember Me"
            center
            checked={remember}
            onPress={() => this.setState(prevState => (
              { ...prevState, remember: !prevState.remember }
            ))}
            containerStyle={styles.formCheckbox}
          />
          <View style={styles.formButton}>
            <Button
              onPress={() => this.handleRegister()}
              title="Register"
              icon={<Icon name="user-plus" type="font-awesome" size={24} color="white" />}
              buttonStyle={{ backgroundColor: '#512AD8' }}
            />
          </View>
        </View>
      </ScrollView>
    );
  }
}

export default RegisterTab;
