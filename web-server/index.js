// rtc object
var rtc = {
    client: null,
    joined: false,
    published: false,
    localStream: null,
    remoteStreams: [],
    params: {}
};


// Create a client
rtc.client = AgoraRTC.createClient({ mode: "live", codec: "h264" });

// Initialize the client
rtc.client.init(option.appID, function() {
    console.log("init success");
}, (err) => {
    console.error(err);
});

rtc.client.setClientRole("audience");

rtc.client.join(option.token ? option.token : null, option.channel, option.uid ? +option.uid : null, function(uid) {
    console.log("join channel: " + option.channel + " success, uid: " + uid);
    rtc.params.uid = uid;
}, function(err) {
    console.error("client join failed", err)
})

rtc.client.on("stream-added", function(evt) {
    var remoteStream = evt.stream;
    var id = remoteStream.getId();
    if (id !== rtc.params.uid) {
        rtc.client.subscribe(remoteStream, function(err) {
            console.log("stream subscribe failed", err);
        })
    }
    console.log('stream-added remote-uid: ', id);
});

rtc.client.on("stream-subscribed", function(evt) {
    var remoteStream = evt.stream;
    var id = remoteStream.getId();
    // Add a view for the remote stream.
    addView(id);
    // Play the remote stream.
    remoteStream.play("remote_video_" + id);
    console.log('stream-subscribed remote-uid: ', id);
})

rtc.client.on("stream-removed", function(evt) {
    var remoteStream = evt.stream;
    var id = remoteStream.getId();
    // Stop playing the remote stream.
    remoteStream.stop("remote_video_" + id);
    // Remove the view of the remote stream. 
    removeView(id);
    console.log('stream-removed remote-uid: ', id);
})

function addView(id, show) {
    if (!$("#" + id)[0]) {
        $("<div/>", {
            id: "remote_video_panel_" + id,
            class: "video-view",
        }).appendTo("#video");
        $("<div/>", {
            id: "remote_video_" + id,
            class: "video-placeholder",
        }).appendTo("#remote_video_panel_" + id);
        $("<div/>", {
            id: "remote_video_info_" + id,
            class: "video-profile " + (show ? "" : "hide"),
        }).appendTo("#remote_video_panel_" + id);
        $("<div/>", {
            id: "video_autoplay_" + id,
            class: "autoplay-fallback hide",
        }).appendTo("#remote_video_panel_" + id);
    }

}

function removeView(id) {
    if ($("#remote_video_panel_" + id)[0]) {
        $("#remote_video_panel_" + id).remove();
    }
}