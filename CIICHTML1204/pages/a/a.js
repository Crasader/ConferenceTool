// pages/a/a.js
const app = getApp()
// var user = require('../../utils/user.js');
// var api = require('../../config/api.js');
// var util = require('../../utils/util.js');
Page({
  data: {
    showView: true,
    animationData: {}
  },

  // onGotUserInfo: function (e) {
  //   let that = this;
  //   if (e.detail.userInfo) {
  //     let userInfo = wx.getStorageSync('userInfo');
  //     console.log('获取缓存2018-11-28')
  //     console.log(userInfo)
  //     if (userInfo) {
  //       wx.redirectTo({
  //         url: "/pages/index/index"
  //       })
  //     } else {
  //       //缓存不存在 则先请求获取基础数据
  //       that.wxLogin(e.detail);
  //       wx.redirectTo({
  //         url: "/pages/welcome1/welcome1"
  //       })
  //     }
  //   }

  // },

  // // 微信登陆
  // wxLogin: function (e) {
  //   let that = this;
  //   let app = getApp();
  //   user.loginByWeixin2(e).then(res => {
  //     wx.hideLoading();
  //     if (res.code == 200) {
  //       //登录成功
  //       let userInfo = res.data;
  //       that.setData({
  //         userInfo: userInfo
  //       });
  //       wx.setStorageSync('userInfo', userInfo);
  //     }
  //   });
  // },

  onLoad: function (options) {
    
  },

  
})