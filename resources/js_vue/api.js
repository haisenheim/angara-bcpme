import axios from 'axios'
import store from './store'

const apiClient = axios.create({
    withCredentials: false, // This is the default
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json'
    }
});

apiClient.interceptors.request.use(function (config) {
    let authKey = store.state.token
    config.headers['Authorization'] = `Bearer ${authKey}`;
    config.headers['Access-Control-Allow-Origin'] = "*";
    return config;
});

export default apiClient;
