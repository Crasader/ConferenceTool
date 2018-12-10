// pages/a/a.js
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
    videoimage: "block", //默认显示封面
    medias_img: [],
    medias_vi: "",
    id: 0,
    showDialog2: false,
    my_direct: '',
    background_show:false,
    ld_show: false,
    redirect_appid:'',
    redirect_extra_data:'',
    redirect_path:'',
    cover_img:'',
    hidePlay:true,//
    
    systemInfo: '', //判断机型,
    show_vi: true, //显示视频参数
    show_id_bgc: false,//显示立顿背景图片
  },

  //确定按钮绑定的方法
  wxLogin2: function(e) {
    layer.wxLogin2(e, user, this);
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
  // 隐藏安卓手机播放按钮
  hidePlayBtn:function(){
    var that = this
    wx.getSystemInfo({
      success: function (res) {
        that.setData({
          systemInfo: res,
        })
          if (res.platform == "ios") {
                that.setData({
                  hidePlay:false
                })     
        } else if (res.platform == "android") {
            that.setData({
              hidePlay: true
            })          
        }
      }

    })
  
  },
  onLoad: function(options) {
    var that = this;
    // 当is_direct等于1的时候才显示进入立顿的背景图片
    // if(wx.getStorageSync('is_redirect')==1){
    //   that.setData({
    //     show_id_bgc:true
    //   })
    // }
     that.hidePlayBtn()
    let param = util.getUrlString(options.scene);
   
    that.setData({
      id: param.product_id
    });
    if (!that.data.id) {
      wx.showToast({
        title: '参数错误',
        duration: 2000,
        icon: 'none',
      })
      return;
    }
    let current_page = getCurrentPages();
    that.setData({
      my_direct: '/' + current_page[0].route + '?scene=' + current_page[0].options.scene
    });
    if (wx.getStorageSync('userInfo')) {
    wx.request({
      url: app.globalData.url + "/product/detail",
      method: 'POST',
      header: {
        'content-type': 'Application/Json'
      },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "product_id": that.data.id,
      },
      success: function(res) {
          console.log("res",res)
        if (res.data.code == 401) {
          that.setData({
            showDialog2: true
          });

        } else if (res.data.code == 300) {

          app.globalData.welcome_redirect = that.data.my_direct;
          app.globalData.conference_id = res.data.data.conference_id;

          wx.redirectTo({
            url: '/pages/welcome1/welcome1?scene=' + encodeURIComponent('conference_id=' + app.globalData.conference_id + '&auto=1'),
          });
        } else if (res.data.code == 200) {

          console.log("res",res);
          // console.log(res.data.data.product_img);
          var media_type = res.data.data.product_img;
          app.globalData.conference_id = res.data.data.conference_id;
          //console.log(media_type[i].media_type)

          var media_vi = res.data.data.product_video.url;
          console.log(media_vi)
          console.log("当没上传视频状态打印出的media_vi", app.globalData.empt_vi)
          if (media_vi == app.globalData.empt_vi){
            console.log("成功了没？")
             that.setData({
               show_vi:false
             })
          }
          // 获取封面图片
          var cover_img = res.data.data.cover_img;
           that.setData({
             cover_img: cover_img
           })
          // 获取跳转到立顿小程序的参数
          var redirect_appid = res.data.data.redirect_appid   //appid
          var redirect_extra_data = res.data.data.redirect_extra_data // extraData
          var redirect_path = res.data.data.redirect_path //path
          // 判断是否是立顿那个参数，
          var is_redirect = res.data.data.is_redirect;
           
          //设置全局缓存
          wx.setStorageSync('is_redirect', is_redirect);
          wx.setStorageSync('redirect_appid', redirect_appid);
          wx.setStorageSync('redirect_extra_data', redirect_extra_data);
          wx.setStorageSync('redirect_path', redirect_path);
          if (is_redirect==1){
            // 如果是就跳转到立顿小程序
            that.setData({
               ld_show:true,
              redirect_appid: redirect_appid,
              redirect_extra_data: redirect_extra_data,
              redirect_path: redirect_path,
              show_id_bgc:true, //显示立顿背景图
              background_show:false //让视频和图片不显示
            })
          }
          that.setData({
            medias_img: media_type,
            medias_vi: media_vi,
            background_show:true,
          })
          that.wxAction(7);
        }
      }
    });
    }else{
      that.setData({
        showDialog2: true,
      });
    }
  },
  // 点击进入立顿小程序
  enter_ld:function(){
    var that = this
    console.log("that",that)
    console.log("appid",that.data.redirect_appid)
    that.setData({
      background_show: false,
    })
    wx.navigateToMiniProgram({
      appId: that.data.redirect_appid,
      path: that.data.redirect_path,
      extraData: that.data.redirect_extra_data,
      // envVersion: 'develop',
      success(res) {
        // 打开成功
        that.wxAction(8);
      }
    })
  },
  //点击播放按钮，封面图片隐藏,播放视频
  bindplay: function(e) {
    this.setData({
        tab_image: "none"
      }),
      this.videoCtx.play()
  },
  onReady() {
    this.videoCtx = wx.createVideoContext('myVideo')
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

      success: function(res) {
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