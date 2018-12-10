//index.js
//获取应用实例
const app = getApp()
var user = require('../../utils/user.js');
var api = require('../../config/api.js');
var util = require('../../utils/util.js');
var layer = require('../../utils/layer.js');
Page({
  data: {
    footerHeight:"",
    show: "",
    array: [],
    showDialog: false,
    showDialog2: false,
    conference_id: app.globalData.conference_id,
  },
  goIndex:function(){
    setTimeout(function () {
     wx.redirectTo({
       url: '/pages/index/index',
     })
    }, 1000);
  },
  goMeet_data: function () {
    setTimeout(function () {
     wx.redirectTo({
       url: '/pages/meet_data/meet_data',
     })
    }, 1000);
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
  //弹窗
  // cloce_layer: function (e) {

  //   this.setData({
  //     showDialog2: true
  //   });
  // },


  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function (e) {
    console.log('这是首页会议id参数111:'+app.globalData.conference_id)
    var that=this;
    that.setData({
      showDialog: !that.data.showDialog,
      conference_id: app.globalData.conference_id,
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
    wx.request({
      url: app.globalData.url + "/agenda/list",
      method: 'POST',
      header: { 'content-type': 'Application/Json' },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "conference_id": app.globalData.conference_id,
      },
      success: function (res) {
         console.log(res);
        if (res.data.code == 401) {
          that.setData({
            showDialog2: true
          });
        }
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
        "conference_id": app.globalData.conference_id,
        "action_type_id":2,
      },
      success: function (res) {
        // console.log(res);
        if (res.data.code == 401) {
          that.setData({
            showDialog2: true
          });
        }
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
        console.log('扫描结果22222')
        console.log("scancode",res.path)
        var path = decodeURIComponent(res.path)
         console.log("处理过的path",path)
        console.log('替换后的url')
   
  

        wx.navigateTo({
          url: '/' + res.path
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
