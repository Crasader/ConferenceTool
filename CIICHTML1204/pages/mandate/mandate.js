// pages/mandate/mandate.js
var app = getApp()
var user = require('../../utils/user.js');
var api = require('../../config/api.js');
var util = require('../../utils/util.js');
var layer = require('../../utils/layer.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {

  },
   add: function(){
      
   },
  //确定按钮绑定的方法
  wxLogin2: function (e) {
    layer.wxLogin2(e, user, this);
  },

  onGotUserInfo: function (e) {
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
  wxLogin: function (e) {
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
        //日志完成，判断是否显示导航
        that.wxIsfirst(e.detail);


      }
    });
  },



  //判断是否首次进入
  wxIsfirst: function () {
    let that = this;
    let conference_id = app.globalData.conference_id;
    wx.request({
      url: app.globalData.url + "/action/isfirst",
      method: 'POST',
      header: {
        'content-type': 'Application/Json'
      },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "conference_id": app.globalData.conference_id,
        "action_type_id": 1,
      },
      success: function (res) {
        // console.log(res);
        if (res.data.code == 401) {
          that.setData({
            showDialog2: true
          });
        }
        if (res.data.code == 200) {
          console.log(111111);
          if (res.data.data.is_first == 1) {
            console.log('首次登录')
            wx.redirectTo({
              url: "/pages/welcome1/welcome1"
            });
          }else if(res.data.data.is_first == 0) {
            console.log(res.data.data.is_first);
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
  onLoad: function (options) {
    console.log('31331231231232132')
  

    //获取二维码的参数
    let param = util.getUrlString(options.scene);
    if (!param) {
      console.log('1-无法获取get参数');
    }

    app.globalData.conference_id = param.conference_id;//将会议id,放到全局变量中去

   
    app.globalData.welcome_redirect = '';//清空数据

    if(wx.getStorageSync('userInfo')){
     wx.redirectTo({
          url: "/pages/index/index?conference_id =" + app.globalData.conference_id
      });
    }


    let isIphoneX = app.globalData.isIphoneX;
    this.setData({
      isIphoneX: isIphoneX
    });
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})