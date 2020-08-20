<template>
    <button type="button" @click="connectToArm()" class="btn "
            :class="(stateVal==0?'btn-primary':(stateVal==1?'btn-danger':(stateVal==2?'btn-success':'btn-secondary')))">
        {{actionText}} to Arm
    </button>
</template>

<script>
    const MSERIAL_CONNECT = 0;
    const MSERIAL_CONNECTION_FAILED = 1;
    const MSERIAL_CONNECTED = 2;
    const SerialPort = require('serialport')
    var port = null;
    export default {
        name: "SerialPortManager",
        props: {
            instructionChannelName: String
        },
        data: function () {
            return {
                stateVal: MSERIAL_CONNECT,
            }
        },
        computed: {
            actionText() {
                switch (this.stateVal) {
                    case MSERIAL_CONNECT:
                        return "Connect";
                    case MSERIAL_CONNECTION_FAILED:
                        return "Failed To Connect"
                    case MSERIAL_CONNECTED:
                        return "Connected"
                    default:
                        return "Unknown Connection State"
                }
            }
        },
        methods: {
            connectToArm() {
                let _this = this;

                if (_this.roboticArmSerialPort == "") {
                    _this.stateVal = MSERIAL_CONNECTION_FAILED;
                    return;
                }

                if (_this.stateVal == MSERIAL_CONNECTED) {
                    port.close((err) => {
                        console.log(err);
                        if (err == null) {
                            _this.stateVal = MSERIAL_CONNECT;
                            if (_this.instructionChannelName !== "")
                                PubNub.unsubscribe({
                                    channels: [_this.instructionChannelName]
                                })
                            return;
                        }
                    });
                    return;
                }

                port = new SerialPort(_this.roboticArmSerialPort, {baudRate: _this.roboticArmBaudRate}, function (err) {
                    if (err == null) {
                        _this.stateVal = MSERIAL_CONNECTED;
                        PubNub.addListener({
                            message: _this.NubscriptionMessage,
                        })
                        if (_this.instructionChannelName !== "")
                            PubNub.subscribe({
                                channels: [_this.instructionChannelName]
                            });
                        return;
                    }
                    console.log(err)
                    _this.stateVal = MSERIAL_CONNECTION_FAILED;
                })


            },

            NubscriptionMessage(message) {
                let channelName = message.channel
                if (!message.message.hasOwnProperty("servo_data")) return;

                this.writeToPort(message.message.servo_data);
            },

            writeToPort(message) {
                let endOfMessage = '\r\n';
                if (!port.isOpen) return;

                message = (!message.endsWith(endOfMessage)) ? message + endOfMessage : message;
                port.write(message);
            },

        },
        watch: {
            instructionChannelName: {
                handler(newVal, oldVal) {
                    if (oldVal !== "")
                        PubNub.unsubscribe({
                            channels: [oldVal]
                        })

                    if (newVal !== "")
                        PubNub.subscribe({
                            channels: [newVal]
                        });
                }
            }
        }

    }
</script>

<style scoped>

</style>