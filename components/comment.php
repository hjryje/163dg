<div id="ArtalkComments" class="container"></div>
<script>
    new Artalk({
        el: '#ArtalkComments', // 元素选择
        placeholder: '来啊，快活啊 ( ゜- ゜)', // 占位符
        noComment: '快来成为第一个评论的人吧~', // 无评论时显示
        defaultAvatar: 'mp', // 参考 https://cn.gravatar.com/site/implement/images/#default-image
        pageKey: '<?php echo $web_url;?>?comment',
        serverUrl: 'https://artalk.storming.ltd/',
        readMore: { // 阅读更多配置
            pageSize: 15, // 每次请求获取评论数
            autoLoad: true // 滚动到底部自动加载
        }
    });
</script>
