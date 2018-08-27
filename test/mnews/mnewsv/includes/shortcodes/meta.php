<div class="shortcodes_control">
    <p>
        <?php _e( '如果你想要使用简码请选择简码选项：', 'salong'); ?>
    </p>
    <div>
        <label>
            <?php _e( '选择简码', 'salong'); ?><span></span></label>
        <select name="items" class="shortcode_sel" size="1" onchange="document.forms.post.items_accumulated.value = this.options[selectedIndex].value;">

            <option class="parentscat">
                <?php _e( '1.消息框简码', 'salong'); ?>
            </option>
            <option value="[infobox]<?php _e('远方的雪山','salong'); ?>[/infobox]">
                <?php _e( '信息框', 'salong'); ?>
            </option>
            <option value="[successbox]<?php _e('远方的雪山','salong'); ?>[/successbox]">
                <?php _e( '成功框', 'salong'); ?>
            </option>
            <option value="[warningbox]<?php _e('远方的雪山','salong'); ?>[/warningbox]">
                <?php _e( '警告框', 'salong'); ?>
            </option>
            <option value="[errorbox]<?php _e('远方的雪山','salong'); ?>[/errorbox]">
                <?php _e( '错误框', 'salong'); ?>
            </option>

            <option class="parentscat">
                <?php _e( '2.按钮简码', 'salong'); ?>
            </option>
            <option value="[scbutton link=&quot;#&quot; target=&quot;blank&quot; variation=&quot;red&quot;]<?php _e('远方的雪山','salong'); ?>[/scbutton]">
                <?php _e( '红色', 'salong'); ?>
            </option>
            <option value="[scbutton link=&quot;#&quot; target=&quot;blank&quot; variation=&quot;yellow&quot;]<?php _e('远方的雪山','salong'); ?>[/scbutton]">
                <?php _e( '黄色', 'salong'); ?>
            </option>
            <option value="[scbutton link=&quot;#&quot; target=&quot;blank&quot; variation=&quot;blue&quot;]<?php _e('远方的雪山','salong'); ?>[/scbutton]">
                <?php _e( '蓝色', 'salong'); ?>
            </option>
            <option value="[scbutton link=&quot;#&quot; target=&quot;blank&quot; variation=&quot;green&quot;]<?php _e('远方的雪山','salong'); ?>[/scbutton]">
                <?php _e( '绿色', 'salong'); ?>
            </option>

            <option class="parentscat">
                <?php _e( '3.列表简码', 'salong'); ?>
            </option>
            <option value="[ssredlist]<ul> <li><?php _e('远方的雪山','salong'); ?></li> <li><?php _e('远方的雪山','salong'); ?></li> <li><?php _e('远方的雪山','salong'); ?></li> </ul>[/ssredlist]">
                <?php _e( '小红点', 'salong'); ?>
            </option>
            <option value="[ssyellowlist]<ul> <li><?php _e('远方的雪山','salong'); ?></li> <li><?php _e('远方的雪山','salong'); ?></li> <li><?php _e('远方的雪山','salong'); ?></li> </ul>[/ssyellowlist]">
                <?php _e( '小黄点', 'salong'); ?>
            </option>
            <option value="[ssbluelist]<ul> <li><?php _e('远方的雪山','salong'); ?></li> <li><?php _e('远方的雪山','salong'); ?></li> <li><?php _e('远方的雪山','salong'); ?></li> </ul>[/ssbluelist]">
                <?php _e( '小蓝点', 'salong'); ?>
            </option>
            <option value="[ssgreenlist]<ul> <li><?php _e('远方的雪山','salong'); ?></li> <li><?php _e('远方的雪山','salong'); ?></li> <li><?php _e('远方的雪山','salong'); ?></li> </ul>[/ssgreenlist]">
                <?php _e( '小绿点', 'salong'); ?>
            </option>

            <option class="parentscat">
                <?php _e( '4.视频简码', 'salong'); ?>
            </option>
            <option value="[youku id=&quot;youku1&quot; youku_id=&quot;<?php _e('优酷视频 ID','salong'); ?>&quot; height=&quot;416&quot;][/youku]">
                <?php _e( '优酷视频', 'salong'); ?>
            </option>
            <option value="[sl_video source=&quot;<?php _e('HTML5视频地址','salong'); ?>&quot; cover=&quot;<?php _e('HTML5视频封面地址','salong'); ?>&quot; height=&quot;416&quot;][/sl_video]">
                <?php _e( 'HTML5视频', 'salong'); ?>
            </option>
            <option value="[sl_audio source=&quot;<?php _e('HTML5音频地址','salong'); ?>&quot;][/sl_audio]">
                <?php _e( 'HTML5音频', 'salong'); ?>
            </option>
            <option value="[ali id=&quot;ali1&quot; ali_id=&quot;<?php _e('阿里云视频点播视频 ID','salong'); ?>&quot;][/ali]">
                <?php _e( '阿里云视频点播视频', 'salong'); ?>
            </option>

            <option class="parentscat">
                <?php _e( '5.其它简码', 'salong'); ?>
            </option>
            <option value="[related_posts tagid=&quot;<?php _e('标签 ID','salong'); ?>&quot;]">
                <?php _e( '标签相关文章', 'salong'); ?>
            </option>
            <option value="[reply]<?php _e('评论后可见内容','salong'); ?>[/reply]">
                <?php _e( '评论后可见内容', 'salong'); ?>
            </option>
            <option value="[private]<?php _e('只有登录用户才能看到的内容','salong'); ?>[/private]">
                <?php _e( '登录用户查看的内容', 'salong'); ?>
            </option>
            <option value="[role id=&quot;2&quot;]<?php _e('哪些角色查看的内容','salong'); ?>[/role]">
                <?php _e( '哪些角色查看的内容', 'salong'); ?>
            </option>
            <option value="[buy product_id=&quot;<?php _e('产品 ID','salong'); ?>&quot;]<?php _e('购买产品才能查看的内容','salong'); ?>[/buy]">
                <?php _e( '购买产品才能查看的内容', 'salong'); ?>
            </option>
        </select>
        <label>
            <?php _e( '简码预览', 'salong'); ?><br><span><?php _e('注：复制简码到编辑器(文本模式下)中，修改成自己的内容，如果有多个视频，『id』后面的值不能一样。','salong'); ?></span></label>
        <p>
            <textarea name="items_accumulated" rows="4"></textarea>
        </p>
    </div>
</div>