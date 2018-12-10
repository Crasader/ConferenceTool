// pages/welcome1/welcome1.js
var app = getApp()
var user = require('../../utils/user.js');
var api = require('../../config/api.js');
var util = require('../../utils/util.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    conference_img: null,
    conference_id:3
  },
  onGotUserInfo: function(e) {
    let that = this;
    if (e.detail.userInfo) {
      let userInfo = wx.getStorageSync('userInfo');
      console.log('没有缓存时进入欢迎获取缓存2018-12-02')
      console.log(userInfo);
      // if (userInfo) {
      //   wx.redirectTo({
      //     url: "/pages/index/index"
      //   })
      // } else {
      //   //缓存不存在 则先请求获取基础数据
        that.wxLogin(e.detail);
      //   wx.redirectTo({
      //     url: "/pages/welcome1/welcome1"
      //   })
      // }
    }
  },

  // 微信登陆
  wxLogin: function(e) {
    let that = this;
    let app = getApp();
    user.loginByWeixin2(e).then(res => {
      wx.hideLoading();
      if (res.code == 200) {
        //登录成功
        let userInfo = res.data;
        that.setData({
          userInfo: userInfo
        });
        wx.setStorageSync('userInfo', userInfo);
        //登陆完成，记录进入会议日志1
        that.wxAction1(e.detail);
        //日志完成，判断是否显示导航
        that.wxIsfirst(e.detail);
      }
    });
  },
 
  //记录行为日志
  wxAction1:function(){
    var conference_id = this.data.conference_id
   wx.request({
      url: app.globalData.url + "/action/add",
      method: 'POST',
      header: {
        'content-type': 'Application/Json'
      },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "conference_id": 3,
        "action_type_id": 1,
      },
      success: function(res) {
        // console.log(res);
        if (res.data == 200) {
          console.log(res.data.msg);
        } else {
          console.log(res.data.msg);
        }
      },
    });
  },
  
  //判断是否首次进入
  wxIsfirst: function () {
    let that = this;
    let conference_id = that.data.conference_id
    wx.request({
      url: app.globalData.url + "/action/isfirst",
      method: 'POST',
      header: {
        'content-type': 'Application/Json'
      },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "conference_id": 3,
        "action_type_id": 1,
      },
      success: function(res) {
        // console.log(res);
        if (res.data.code == 200) {
          if (res.data.data.is_first == 1) {
            setTimeout(function () {
              wx.redirectTo({
                url: "/pages/welcome2/welcome2"
              });
            });
          }else{
            setTimeout(function () {
              wx.redirectTo({
                url: "/pages/index/index?conference_id =" + conference_id
              });
            });
          }
        } else {
          wx.showToast({
            title: res.data.msg,
            icon: 'none',
            duration: 2000
          })
        }
      },
    });
  },


  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    console.log("这是欢迎页会议id------2018.12.03")
    console.log(options)
    let conference_id = options.conference_id;
    app.globalData.conference_id = conference_id
    console.log("app.globalData.conference_id")
    console.log(app.globalData.conference_id)
    this.setData({
      conference_id: conference_id
    });
    let userInfo = wx.getStorageSync('userInfo');
    console.log('获取缓存2018-12-02')
    console.log(userInfo);
    if (userInfo) {
      wx.redirectTo({
        url: "/pages/index/index?conference_id =" + conference_id
      });
    }
    app.globalData.conference_id = options.conference_id;
    var that = this;
    // console.log(res.data.msg);
    //判断加welcome1，图片
    wx.request({
      url: app.globalData.url + "/conference/image",
      method: 'POST',
      header: {
        'content-type': 'Application/Json'
      },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "conference_id": 3,
      },
      success: function(po) {
        console.log(po)
        if (po.data.code == 200) {
          that.setData({
            conference_img: po.data.data.conference_img
          });
        } else {
          wx.showToast({
            title: po.data.msg,
            icon: 'none',
            duration: 2000
          })
        }
      }
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function() {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function() {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function() {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function() {

  }
})