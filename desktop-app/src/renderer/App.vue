<template>
    <div id='app'>
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <span class="navbar-brand" href="#">
                <img src="./assets/health.png" class="mr-2" alt="Logo" height="32"/>
                BioPulse
            </span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <!--                    <li class="nav-item" data-component="Products">-->
                    <!--                        <span class="nav-link">CRUD</span>-->
                    <!--                    </li>-->
                    <!--                    <li class="nav-item" data-component="Text">-->
                    <!--                        <span class="nav-link">Text</span>-->
                    <!--                    </li>-->
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="#settingsModal" data-toggle="modal" class="nav-link">Settings</a>
                    </li>
                    <li class="nav-item">
                        <serial-port-manager
                                :instruction-channel-name="sessionData.instruction_channel"></serial-port-manager>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="pt-4" :class="hasActiveSession?'container-fluid':'container'">
            <div class="row justify-content-center" v-if="!hasActiveSession">
                <div class="col-md-9">
                    <div class="card mt-3">
                        <h5 class="card-header">No Active Session</h5>
                        <div class="card-body text-center">
                            <h3 class="text-warning" style="font-size: 500%">
                                <i class="fas fa-exclamation-triangle"></i>
                            </h3>
                            <p>You do not currently have an active session, to start a session start press the button
                                below.</p>
                            <button @click="createNewSession()" class="btn btn-lg btn-outline-secondary" type="button">
                                Start Session
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="hasActiveSession" class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Preview:</h5>
                            <div class="video text-center " id="local"
                                 style="background-color: #2a303c; margin-bottom: -0.5rem; margin-left: -0.5rem; margin-right: -0.5rem">
                                <i class="fas fa-camera text-white"
                                   style="opacity: 0.5;position:relative; top: calc(50% - 30px); font-size: 500%"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card session-details" style="width: 100%">
                        <div class="card-body">
                            <h5 class="card-title">Session Details:</h5>
                            <form>
                                <div class="form-group">
                                    <label for="frmUserKey">User Key</label>
                                    <input type="text" readonly class="form-control-plaintext"
                                           id="frmUserKey" v-model="sessionData.user_key">
                                </div>
                                <div class="form-group">
                                    <label for="frmUserPass">User Pass</label>
                                    <input type="text" readonly class="form-control-plaintext"
                                           id="frmUserPass" v-model="sessionData.user_pass">
                                </div>
                                <div class="form-group">
                                    <label for="frmSessionDate">Session Started At</label>
                                    <input type="text" readonly class="form-control-plaintext"
                                           id="frmSessionDate" v-model="sessionData.created_at">
                                </div>
                                <div class="form-group text-center">
                                    <button type="button" class="btn btn-secondary" @click="endCurrentSession()">
                                        Terminate Session
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="console"></div>

            <!-- Modal -->
            <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Settings</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="armSerialPort">Robotic Arm Serial Port</label>
                                    <div class="input-group">
                                        <select v-model="selectedPort" class="form-control" id="armSerialPort">
                                            <option v-for="(port, index) in ports" :value="port.path">
                                                {{port.path}}
                                            </option>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" @click="listPorts()"
                                                    class="btn btn-outline-secondary"><i
                                                    class="fas fa-redo"></i></button>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="armSerialBaud">Serial Port Baud Rate
                                        {{selectedBaud}}-{{roboticArmBaudRate}}</label>
                                    <select v-model="selectedBaud" class="form-control" id="armSerialBaud">
                                        <option v-for="(baud, index) in baudRateList" :value="baud">
                                            {{baud}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="webcamDevice">Webcam Device</label>
                                    <div class="input-group">
                                        <select v-model="selectedVideo" class="form-control" id="webcamDevice">
                                            <option v-for="(videoD, index) in videoList" :value="videoD.deviceid">
                                                {{videoD.devicename}}
                                            </option>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" @click="listVideoDevices()"
                                                    class="btn btn-outline-secondary"><i
                                                    class="fas fa-redo"></i></button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" @click="updateSettings()">Save changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>
</template>

<script>
    import SerialPortManager from './components/SerialPortManager.vue'
    import AgoraRtcEngine from 'agora-electron-sdk'
    import path from 'path'
    import os from 'os'

    const SerialPort = require("serialport");

    // With shell.openExternal(url) is how
    // external urls must be handled, not href
    const shell = require('electron').shell
    const APPID = process.env["AGORA_APPID"] || ""
    var rtcEngine = new AgoraRtcEngine();
    rtcEngine.initialize(APPID);

    export default {
        components: {
            SerialPortManager
        },
        data: function () {
            let _this = this;
            return {
                ports: [],
                baudRateList: [300, 600, 1200, 2400, 4800, 9600, 14400, 19200, 28800, 38400, 57600, 115200],
                videoList: [],
                selectedPort: null,
                selectedBaud: null,
                selectedVideo: null,
                hasActiveSession: false,
                sessionData: {
                    user_key: null,
                    user_pass: null,
                    video_channel: null,
                    instruction_channel: null,
                    created_at: null
                }
            }
        },
        created: function () {
            this.selectedPort = this.roboticArmSerialPort;
            this.selectedBaud = this.roboticArmBaudRate;
            this.selectedVideo = this.videoDefault;
            this.initialAccountGet()
        },
        methods: {
            link: (url) => {
                shell.openExternal(url)
            },
            initialAccountGet() {
                let _this = this;
                axios.get(ServerUrl + "api.php", {
                    params: {
                        robot_key: RobotKey,
                        module: "accounts",
                        action: "get_account"
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        if (response.data.data.user_key != null) {
                            _this.activateSession(response.data.data);
                        }
                    }
                });
            },
            createNewSession() {
                let _this = this;
                axios.get(ServerUrl + "api.php", {
                    params: {
                        robot_key: RobotKey,
                        module: "accounts",
                        action: "start_session"
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        if (response.data.data.user_key != null) {
                            _this.activateSession(response.data.data);
                        }
                    }
                });
            },
            endCurrentSession() {
                let _this = this;
                axios.get(ServerUrl + "api.php", {
                    params: {
                        robot_key: RobotKey,
                        module: "accounts",
                        action: "end_session"
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        _this.hasActiveSession = false;
                        _this.sessionData = {
                            user_key: null,
                            user_pass: null,
                            video_channel: null,
                            instruction_channel: null,
                            created_at: null
                        };
                    }
                });
            },
            activateSession(session_data) {
                this.hasActiveSession = true;
                this.sessionData = session_data;
            },
            async listPorts() {
                let _this = this;
                _this.ports = await SerialPort.list();

            },
            listVideoDevices() {
                if (rtcEngine) {
                    this.videoList = rtcEngine.getVideoDevices();
                }
                //console.log(this.videoList);
            },
            updateSettings() {
                this.$store.set('roboticArmSerialPort', this.selectedPort);
                this.$store.set('roboticArmBaudRate', this.selectedBaud);
                this.$store.set('videoDefault', this.selectedVideo);
                if (rtcEngine) {
                    rtcEngine.setVideoDevice(this.selectedVideo);
                    // console.log(this.selectedVideo);
                }
                $("#settingsModal").modal("hide");
            },
            joinChannel(channel) {
                if (rtcEngine)
                    rtcEngine.joinChannel(null, channel, null, RobotKey);
            }
        },
        mounted: function () {
            let _this = this;
            this.listPorts();
            this.$nextTick(function () {
                // Code that will run only after the
                // entire view has been rendered
                if (global.rtcEngine) {
                    global.rtcEngine.release()
                    global.rtcEngine = null
                }

                if (!APPID) {
                    alert('Please provide Agora APPID in Env file')
                    return
                }

                const consoleContainer = document.querySelector('#console')

                rtcEngine = new AgoraRtcEngine();
                rtcEngine.initialize(APPID);
                // consoleContainer.innerHTML = "Console Present";

                // listen to events
                rtcEngine.on('joinedChannel', (channel, uid, elapsed) => {
                    consoleContainer.innerHTML = `join channel success ${channel} ${uid} ${elapsed}`
                    if (this.videoDefault != "") {
                        rtcEngine.setVideoDevice(this.videoDefault);
                    }
                    let localVideoContainer = document.querySelector('#local')
                    localVideoContainer.innerHTML = "";
                    //setup render area for local user
                    rtcEngine.setupLocalVideo(localVideoContainer)
                })
                rtcEngine.on('error', (err, msg) => {
                    consoleContainer.innerHTML = `error: code ${err} - ${msg}`
                })
                // rtcEngine.on('userJoined', (uid) => {
                //     //setup render area for joined user
                //     let remoteVideoContainer = document.querySelector('#remote')
                //     rtcEngine.setupViewContentMode(uid, 1);
                //     rtcEngine.subscribe(uid, remoteVideoContainer)
                // })

                // set channel profile, 0: video call, 1: live broadcasting
                rtcEngine.setChannelProfile(1)
                rtcEngine.setClientRole(1)

                // enable video, call disableVideo() is you don't need video at all
                rtcEngine.enableVideo()

                const logpath = path.join(os.homedir(), 'agorasdk.log')
                // set where log file should be put for problem diagnostic
                rtcEngine.setLogFile(logpath)


                global.rtcEngine = rtcEngine
            })
            this.listVideoDevices();

            //this.joinChannel("dfd")
        },
        watch: {
            'sessionData.video_channel': {
                handler(newVal, oldVal) {
                    if (!rtcEngine)
                        return;

                    if (oldVal != null)
                        rtcEngine.leaveChannel();

                    if (newVal != null)
                        rtcEngine.joinChannel(null, newVal, null, Math.floor(new Date().getTime() / 1000))

                    console.log(newVal);

                }
            }
        }
    }
</script>

<style>
    body {
        height: 100vh;
        background-color: #f1f1f1c2;
    }

    #app a {
        color: #42b983;
        text-decoration: none;
    }

    .video {
        /*width: 100%;*/
        height: calc(100vh - 180px);
        overflow: hidden;
    }

    .session-details {
        height: calc(100vh - 110px);
    }

</style>
