// pages/welcome1/welcome1.js
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
        conference_img: null,
        // conference_id: 3
        welcome:'',
        startX: 0,
    },
  
   // 切换页面
   touchStart: function (e) {
      this.setData({
         startX: e.changedTouches[0].pageX
      })
   },
   touchEnd: function (e) {
      let startX = this.data.startX;
      let endX = e.changedTouches[0].pageX;
      // 右滑
      if (startX - endX > 60) {
         wx.navigateTo({
            url: '/pages/welcome2/welcome2',
         })
      }
   },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        console.log("这是welcome1页面")



        let userInfo = wx.getStorageSync('userInfo');

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
                "conference_id": app.globalData.conference_id,
            },
            success: function(po) {
              if (po.data.code == 401) {
                that.setData({
                  showDialog2: true
                });
              }
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
        });
      // setTimeout(() => {
      //   wx.redirectTo({
      //     url: '/pages/welcome2/welcome2'
      //   })
      // }, 2000);
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function() {
      //记录行为日志
        // var conference_id = this.data.conference_id
        wx.request({
          url: app.globalData.url + "/action/add",
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

          },
        });
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