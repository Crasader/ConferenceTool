  
// var NewApiRootUrl = "https://wscrm.prowiser.cn/ciic/index.php" //SVN测试服
var NewApiRootUrl = "https://shanghaiciic.ulcampaign.com/index.php" //CIIC正式服


module.exports = {

  login: NewApiRootUrl + '/ufs_register/index',              //2-2后台终端用户获取open_id和token
  login1: NewApiRootUrl + '/user/login',            //1-1后台供应商获取open_id接口
  Survey: NewApiRootUrl + '/survey/detail',            //1-1后台供应商获取open_id接口
};
