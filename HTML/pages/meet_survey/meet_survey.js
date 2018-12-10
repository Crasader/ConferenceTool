// pages/meet_survey/meet_survey.js
const app = getApp()
var user = require('../../utils/user.js');
var api = require('../../config/api.js');
var util = require('../../utils/util.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    survey_id: 1,
    question_id:0,
    question_type:0,
    title:'',
    showDialog:false,
    showDialog2:false,
    arr: [],
    question_list: [],
    question_options_ids:[],
    mes: '谢谢您的参与',

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
  
  ChoseAnswer: function (e) {
    console.log(e);
    let id = e.currentTarget.dataset.idx - 1;
    console.log(id);
    let question_options_ids = this.data.question_options_ids;

    var d = this.data.arr;
    if (this.data.question_type == 1) {
      question_options_ids = [];
      for (var i = 0; i < d.length; i++) {
        d[i].s = false;
      }

      if (!d[id]) {
        d[id].s = !d[id].s;
      }
    }



    d[id].s = !d[id].s;


    console.log(d[id].s);

    let _index = question_options_ids.indexOf(e.currentTarget.dataset.idx);
    if (_index == -1) {
      question_options_ids.push(e.currentTarget.dataset.idx);
    } else {
      question_options_ids.splice(_index, 1);
    }

    console.log('提交的数组');
    console.log(question_options_ids)

    // d.forEach(function (valu, index) {
    //   console.log(valu);
    //   if (valu.s) {

    //     options_ids.push(valu)
    //   }
    // });
    this.setData({
      question_options_ids: question_options_ids,
    })
    this.setData({
      arr: d,

    })
  },

  //提交问卷
  click: function (e) {
    console.log('按钮参数')
    console.log(e)
    var that = this;

    let survey_id = e.currentTarget.dataset.survey_id
    //提交的问题列表
    var arr = that.data.arr;
    let question_list = [];
    let question_id = that.data.question_id;

    question_list[0] = {
      'question_id': question_id,
      'question_type': this.data.question_type,
      'question_options_ids': this.data.question_options_ids
    };


    console.log('打印问题列表')
    console.log(question_list);
    wx.request({
      url: app.globalData.url + "/survey/submit",
      method: 'POST',
      header: {
        'content-type': 'Application/Json'
      },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "survey_id": survey_id,
        "question_list": question_list,
      },
      success: function (po) {
        console.log('返回值2018.11.30')
        console.log(po.data)
        if (po.data.code == 200) {
          that.setData({
            showDialog: !that.data.showDialog,
            mes: "谢谢您的参与"
          });
          that.setData({
            title: po.data.data[0]['title'],
            arr: po.data.data[0]['question_options']
          });
          console.log(that.data.arr)
        } else {
          that.setData({
            showDialog: !that.data.showDialog,
            mes: "您已提交，请勿重复参与！"
          });
        }
      }
    });

  },
  close: function () {
    wx.redirectTo({
      url: "/pages/index/index"
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var that = this;
    wx.request({
      url: app.globalData.url + "/survey/detail",
      method: 'POST',
      header: {
        'content-type': 'Application/Json'
      },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "survey_id": 1,
      },
      success: function (po) {
        console.log('返回值2018.12.03')
        console.log(po.data.data)
        if (po.data.code == 401){
          that.setData({
            showDialog2: !that.data.showDialog2,
            mes: "用户信息已失效，点击重新授权。"
          });
          
        };
        
        if (po.data.code == 200) {
          that.setData({
            title: po.data.data[0]['title'],
            arr: po.data.data[0]['question_options'],
            question_id: po.data.data[0]['id'],
            question_type: po.data.data[0]['question_type'],
            question_list : po.data

          });
          console.log('这是返回的问题选项')
          console.log(that.data.arr)
        } else {
          // wx.showToast({
          //   title: po.data.msg,
          //   icon: 'none',
          //   duration: 2000
          // })
        }
      },
    });
    
    //判断用户首次答卷
    wx.request({
      url: app.globalData.url + "/survey/isfirst",
      method: 'POST',
      header: {
        'content-type': 'Application/Json'
      },
      data: {
        "openId": wx.getStorageSync('userInfo').openId,
        "token": wx.getStorageSync('userInfo').token,
        "survey_id": 1,
      },
      success: function (re) {
        if(re.data.code){
          if (re.data.data.is_first !== 1){
            that.setData({
              showDialog: !that.data.showDialog,
              mes: "您已答过问卷"
            });
            
            // setTimeout(function () {
            //   wx.redirectTo({
            //     url: "/pages/index/index"
            //   });
            // }, 2000)
            
          }
        }
      }

    });
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