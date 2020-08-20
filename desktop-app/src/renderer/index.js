/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');
    window.axios = require("axios");

    require('bootstrap');
    let PubNubObj = require('pubnub')

    window.PubNub = new PubNubObj({
        publishKey: process.env["PUBNUB_PUBLISHKEY"] || "",
        subscribeKey: process.env["PUBNUB_SUBSCRIBEKEY"] || "",
        uuid: process.env["PUBNUB_UUID"] || ""
    })

    window.ServerUrl = "http://bio.test/";
    window.RobotKey = "jmugonix-one";

} catch (e) {
}


import Vue from 'vue'

import persistentState from 'vue-persistent-state'

let initialState = {
    roboticArmSerialPort: "",
    roboticArmBaudRate: 9600,
    videoDefault: "",
}

// inject initialState as data
Vue.use(persistentState, initialState)

import App from './App.vue'

new Vue({
    el: '#app',
    render: h => h(App)
})