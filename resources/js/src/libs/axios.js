import Vue from 'vue'

// axios
import axios from 'axios'

const axiosIns = axios.create({
  // You can add your headers here
  // ================================
  baseURL: window.location.origin + '/api/',
  // timeout: 1000,
  headers: {
    // 'X-Custom-Header': 'foobar',
    "Content-Type": 'application/json',
    "Accept": "application/json",
    "Access-Control-Allow-Origin": "*",
    'Access-Control-Allow-Credentials':true,
  }
})

Vue.prototype.$http = axiosIns

export default axiosIns
