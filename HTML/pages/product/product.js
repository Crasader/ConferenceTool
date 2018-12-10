// pages/a/a.js
const app = getApp()
var user = require('../../utils/user.js');
var api = require('../../config/api.js');
var util = require('../../utils/util.js');
Page({
  /**
   * 页面的初始数据
   */
  data: {
    videoimage: "block", //默认显示封面
    medias_img: [],
    medias_vi:"",
    id:0,
    showDialog2: false,
  },

  onGotUserInfo: function (e) {
    let that = this;
    if (e.detail.userInfo) {
      let userInfo = wx.getStorageSync('userInfo');
      console.log('没有缓存时进入欢迎获取缓存2018-12-02')
      console.log(userInfo);
      that.wxLogin(e.detail);
    }
  },

  // 微信登陆
  wxLogin: function (e) {
    let that = this;
    let app = getApp();
    user.loginByWeixin2(e).then(res => {
      wx.hideLoading();
      if (res.code == 200) {
        //登录成功
        let userInfo = res.data;
        // that.setData({
        //   userInfo: userInfo
        // });
        wx.setStorageSync('userInfo', userInfo);
        that.setData({
          showDialog2: !that.data.showDialog2,

        });
      }
    });
  },

  onLoad: function (options) {
    var that = this;
    let id = options.id;
    that.setData({
      id: id
    });
    wx.request({
      url: app.globalData.url + "/product/detail",
      method: 'POST',
      header: { 'content-type': 'Application/Json' },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "product_id": that.data.id,
      },
      success: function (res) {
        // console.log(res);
        // console.log(res.data.data.product_img);
        var media_type = res.data.data.product_img;
        
        
          //console.log(media_type[i].media_type)
         
            that.setData({
              medias_img: media_type
            })
        
        var media_vi = res.data.data.product_video;
        console.log(media_vi)
        that.setData({
          medias_vi: media_vi
        })


      }
    });

    //行为日志
    wx.request({
      url: app.globalData.url + "/action/add",
      method: 'POST',
      header: {
        'content-type': 'Application/Json'
      },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "conference_id": 1,
        "action_type_id": 7,
        "param_id": id
      },
      success: function (res) {
        // console.log(res);
        if (res.data == 200) {
          console.log(res.data.msg);
        } else {
          console.log(res.data.msg);
        }
      },
    });
  },

  //点击播放按钮，封面图片隐藏,播放视频
  bindplay: function (e) {
    this.setData({
      tab_image: "none"
    }),
      this.videoCtx.play()
  },
  onReady() {
    this.videoCtx = wx.createVideoContext('myVideo')
  },


})
