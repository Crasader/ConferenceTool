//index.js
//获取应用实例
const app = getApp()
var user = require('../../utils/user.js');
var api = require('../../config/api.js');
var util = require('../../utils/util.js');
Page({
  data: {
    footerHeight:"",
    show: "",
    array: [],
    showDialog: false,
    showDialog2: false,
    conference_id: getApp().globalData.userInfo
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


  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function (e) {
    console.log('这是首页会议id参数')
    console.log(e)
    let conference_id = e.conference_id;
    wx.setStorageSync('conference_id', conference_id);
    console.log(this.data.conference_id)
    var that=this;
    that.setData({
      showDialog: !that.data.showDialog,
      conference_id: conference_id
    });
    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
    } else if (this.data.canIUse){
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
      }
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      });
     
    };
  
    var that=this;
    console.log('这是本页参数2018.12.03')
    var conference_id = that.data.conference_id
    wx.request({
      url: app.globalData.url + "/agenda/list",
      method: 'POST',
      header: { 'content-type': 'Application/Json' },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "conference_id":3,
      },
      success: function (res) {
         console.log(res);
          if(res.data.code==200){
            //截取字符
            var expired_time = res.data.data;
            console.log(expired_time)
            for (var i = 0; i < expired_time.length;i++){
              expired_time[i].expired_time ="-" + expired_time[i].expired_time.substring(10, 16);
              that.setData({
                array: expired_time,
              });
            }
            
          }else{
            wx.showToast({
              title: res.data.msg,
              icon: 'none',
              duration: 2000
            })
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
        "action_type_id":2,
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

    let isIphoneX = app.globalData.isIphoneX;
    this.setData({
      isIphoneX: isIphoneX
    });
  },
  getUserInfo: function(e) {
    console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  },
  //扫描二维码
  click: function () {
    var that = this;
    var show;
    wx.scanCode({
      success: (res) => {
        console.log('扫描结果')
        console.log(res)
        var url = decodeURIComponent(res.path)
        var url = url.replace('%3D', '3');
        var url = url.replace('scene=', '');
        console.log('替换后的url')
        console.log(url)
        wx.redirectTo({
          url: '/' + url
        })

        // this.show = "结果:" + res.result + "二维码类型:" + res.scanType + "字符集:" + res.charSet + "路径:" + res.path;
        // that.setData({
        //   show: this.show
        // })
        // wx.showToast({
        //   title: '成功',
        //   icon: 'success',
        //   duration: 2000
        // })
      },
      fail: (res) => {
        wx.redirectTo({
          url: '/pages/index/index'
        })
        // wx.showToast({
        //   title: '失败',
        //   icon: 'success',
        //   duration: 2000
        // })
      },
      complete: (res) => {
      }
    })
  }

})
