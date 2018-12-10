function wxLogin2(e, user, that) {

  console.log("eeee")
  console.log(e);
  console.log(e.target.dataset.pages)
  let path = e.target.dataset.pages;
  if (e.detail.userInfo) {
    let userInfo = wx.getStorageSync('userInfo');
    console.log('没有缓存时进入欢迎获取缓存2018-12-02')
    console.log(userInfo);
    // if (userInfo) {
    //   wx.redirectTo({
    //     url: "/pages/index/index"
    //   })
    // } else {
    //   //缓存不存在 则先请求获取基础数据
    wxLogin(e.detail, user, that, path);
    //   wx.redirectTo({
    //     url: "/pages/welcome1/welcome1"
    //   })
    // }
  }
}


// 微信登陆
function wxLogin(e, user, that, path) {
  let app = getApp();
  user.loginByWeixin2(e).then(res => {
    console.log(path);
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

      console.log(path);
      //跳转
      wx.redirectTo({

        url: path
      });
    }
  });
}

function wxAction(rootpath,conference_id,action_type){
 
  
  wx.request({
    url: rootpath + "/action/add",
    method: 'POST',
    header: {
      'content-type': 'Application/Json'
    },
    data: {
      "openId": wx.getStorageSync('userInfo').openId,
      "token": wx.getStorageSync('userInfo').token,
      "conference_id": conference_id,
      "action_type_id": action_type,
    },
    success: function (res) {
      
    },
  });
}


module.exports = {
  wxLogin2,
  wxAction
}


