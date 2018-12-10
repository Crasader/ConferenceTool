// pages/welcome2/welcome2.js
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {

     isActive: false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let _this = this;
    setTimeout(()=>{
       _this.setData({
          isActive: true
       })
    },1000);

    

    //动画
    //   var time, i = 0;
    //   var map_box = document.getElementById("map_box");
    //   var map = map_box.getElementsByClassName("map");
    //   time = setInterval(function () {
    //     if (i < map.length){
    //       map.classList.remove("map_on");
    //       map[i].classList.add("map_on");
    //     } else {
    //      clearInterval(time)
    //     }
    //     i++;
    //     },1000);
    // var that = this;
    var time = 0;

   //  time = setInterval(function () {
   //    that.setData({
   //      num: (that.data.num + 1),
   //    });
   //  }, 600);

    //跳转到index页
   //  setTimeout(function () {
   //    wx.redirectTo({
   //      url: "/pages/index/index"
   //    });
   //  }, 8800);

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