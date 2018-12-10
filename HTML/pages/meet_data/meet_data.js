// pages/meet_data/meet_data.js
const app = getApp()
var user = require('../../utils/user.js');
var api = require('../../config/api.js');
var util = require('../../utils/util.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    show: "",
    met: [],
    prod: [],
    showDialog2:false
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
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    //会议资料
    var that = this;
    wx.request({
      url: app.globalData.url + "/assets/list",
      method: 'POST',
      header: { 'content-type': 'Application/Json' },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "conference_id": 3,
      },
      success: function (res) {
        console.log(res);
        if (res.data.code == 200) {
          that.setData({
            met: res.data.data,
          })
        } else {
          console.log(res.data.msg)
        }
      }
    });

    //新品推介
    var that = this;
    wx.request({
      url: app.globalData.url + "/product/list",
      method: 'POST',
      header: { 'content-type': 'Application/Json' },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "conference_id": 3,
      },
      success: function (res) {
        console.log(res);
        if (res.data.code == 200) {
          that.setData({
            prod: res.data.data,
          })
        } else {
          console.log(res.data.msg)
        }
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
        "conference_id": 3,
        "action_type_id": 3,
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

  //行为日志
  

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

  },
  click: function () {
    var that = this;
    var show;
    wx.scanCode({
      success: (res) => {
        this.show = "结果:" + res.result + "二维码类型:" + res.scanType + "字符集:" + res.charSet + "路径:" + res.path;
        that.setData({
          show: this.show
        })
        wx.showToast({
          title: '成功',
          icon: 'success',
          duration: 2000
        })
      },
      fail: (res) => {
        wx.showToast({
          title: '失败',
          icon: 'success',
          duration: 2000
        })
      },
      complete: (res) => {
      }
    })
  },

  //资料详情
  Assets: function (e) {
    console.log('e')
    console.log(e)
    let id = e.currentTarget.dataset.id;
    let url = e.currentTarget.dataset.url;
    wx.navigateTo({
      url: '../assets/assets?url=' + url + '&id=' + id
    })
  },


  //跳转产品详情
  Product: function (e) {
    console.log(e)
    let id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '../product/product?id='+id
    })
  }
})