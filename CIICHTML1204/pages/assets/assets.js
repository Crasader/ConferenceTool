// pages/assets/assets.js
const app = getApp();
var layer = require('../../utils/layer.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    id: 0,
    url:"",
  },

  //确定按钮绑定的方法
  wxLogin2: function (e) {
    layer.wxLogin2(e, user, this);
  },

  // 微信登陆
  wxLogin: function (e) {
    console.log('青菜');
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
    wx.hideShareMenu();
    wx.setNavigationBarTitle({

      title: "会员资料",

    })
    console.log(options)
    var that = this;
    var url = options.url;
    var id = options.id;
    //获取手机型号，适配iphone手机
    wx.getSystemInfo({
      success: function (res) {
        console.log('操作系统版本 = ' + res.system)
        let modelmes = res.system;
        if (modelmes.search('Android') != -1) {
          wx.downloadFile({
            url: url,
            success: function (res) {
              var filePath = res.tempFilePath
              wx.openDocument({
                filePath: filePath,
                success: function (res) {
                  console.log('打开文档成功')
                }
              })
            }
          })
        }
      }
    })
    that.setData({
      id: id,
      url: url,
    });
    console.log('这是id')
    console.log(that.data.id)
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
        "action_type_id": 4,
        "param_id": id
      },
      success: function (res) {
       
        if (res.data.code == 401) {
          that.setData({
            showDialog2: true
          });
        }
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