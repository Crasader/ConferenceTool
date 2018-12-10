// pages/meet_data/meet_data.js
const app = getApp()
var user = require('../../utils/user.js');
var api = require('../../config/api.js');
var util = require('../../utils/util.js');
var layer = require('../../utils/layer.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    show: "",
    met: [],
    prod: [],
    showDialog2:false,
    interest:true
  },
  goIndex: function () {
      wx.redirectTo({
         url: '/pages/index/index',
      })
  },
  goMeet_data: function () {
    
     wx.redirectTo({
        url: '/pages/meet_data/meet_data',
      })
    
  },
  //确定按钮绑定的方法
  wxLogin2: function (e) {
    layer.wxLogin2(e, user, this);
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
  //弹窗
  // cloce_layer: function (e) {

  //   this.setData({
  //     showDialog2: true
  //   });
  // },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    console.log('资料进来了');
    //会议资料
    var that = this;
    wx.request({
      url: app.globalData.url + "/assets/list",
      method: 'POST',
      header: { 'content-type': 'Application/Json' },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "conference_id": app.globalData.conference_id,
      },
      success: function (res) {
        
        if (res.data.code == 401) {
          that.setData({
            showDialog2: true
          });
        }
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
    // var that = this;
    wx.request({
      url: app.globalData.url + "/product/list",
      method: 'POST',
      header: { 'content-type': 'Application/Json' },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "conference_id": app.globalData.conference_id,
      },
      success: function (res) {
        console.log("获取到列表的信息", res);
        console.log("product_name",res.data.data)
        
        
        if (res.data.code == 401) {
          that.setData({
            showDialog2: true
          });
        }
        if (res.data.code == 200) {
          
          that.setData({
            prod: res.data.data,
          })
          // if (res.data.data.length <1) {
          //   that.setData({
          //     interest: false
          //   });
            
          // }
         
        }
        else if(res.data.code==400){
            that.setData({
              interest: false
            });
        }
         else {
          console.log("没数据打印的是啥",res.data.msg)
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
        "action_type_id": 3,
      },
      success: function (res) {
       
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
  },
  //扫描二维码
  click: function () {
    var that = this;
    var show;
    wx.scanCode({
      success: (res) => {
        console.log('扫描结果')
        console.log(res)

          wx.navigateTo({
          url: '/'+res.path,
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
  // click: function () {
  //   var that = this;
  //   var show;
  //   wx.scanCode({
  //     success: (res) => {
  //       this.show = "结果:" + res.result + "二维码类型:" + res.scanType + "字符集:" + res.charSet + "路径:" + res.path;
  //       that.setData({
  //         show: this.show
  //       })
  //       wx.showToast({
  //         title: '成功',
  //         icon: 'success',
  //         duration: 2000
  //       })
  //     },
  //     fail: (res) => {
  //       wx.showToast({
  //         title: '失败',
  //         icon: 'success',
  //         duration: 2000
  //       })
  //     },
  //     complete: (res) => {
  //     }
  //   })
  // },

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
    console.log("1111",e)
    var that = this
    var is_redirect= wx.getStorageSync('is_redirect');
    var redirect_appid=wx.getStorageSync('redirect_appid');
    var redirect_extra_data=wx.getStorageSync('redirect_extra_data');
    var redirect_path=wx.getStorageSync('redirect_path');
    console.log("啊啊啊啊啊啊",redirect_appid);
    var product_name = e.currentTarget.dataset.name;
    console.log("水水水水水水水", product_name);
    product_name = product_name.substring(0,2)
    if (is_redirect==1 && product_name=="立顿"){
      that.setData({
        // background_show: false,
      })
      wx.navigateToMiniProgram({
        appId: redirect_appid,
        path: redirect_path,
        extraData: redirect_extra_data,
        // envVersion: 'develop',
        success(res) {
          // 打开成功
          that.wxAction(8);
        }
      })
    }else{
      // console.log("获取全局缓存", wx.getStorageSync('is_redirect'))
      let id = e.currentTarget.dataset.id;
      console.log("打印跳转产品id", id)
      that.setData({
        // background_show: false,
      })
      wx.navigateTo({
        url: '../product/product?scene=' + encodeURIComponent('product_id@_@' + id),
      })
      wx.setNavigationBarTitle({
        title: '会议资料',
      })
    }
   

  },

  wxAction: function (action_type_id) {

    var that = this
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
        "action_type_id": action_type_id,
        "param_id": that.data.id
      },

      success: function (res) {
        // console.log(res);
        console.log("qqqq", that.data.id)
        if (res.data.code == 401) {
          that.setData({
            showDialog2: true
          });
        } else if (res.data.code == 200) {
          console.log(res.data.msg);
        } else {
          console.log(res.data.msg);
        }
      },
    });
  },
})