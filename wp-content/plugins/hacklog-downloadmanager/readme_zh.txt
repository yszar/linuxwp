
�����ǰ��װ��wp-downloadmanager ���Ƚ���wp-downloadmanager��
Ȼ������һ�� update_to_hacklog.php:
http://www.�������.com/wp-content/plugins/hacklog-downloadmanager/update_to_hacklog.php
��ʾ���ɹ�ɾ��file_category�У����������ʹ�� wp-downloadmanager ��Ұ�޵��޸İ���-_-!
����ʹ���ˡ�
Ȼ���ɾ�����ļ���

���û�а�װ��wp-downloadmanager��ֱ�Ӽ����������ˡ�

== Changelog ==
=2.1.2=
����jquery�������⣬�������jquery,��Twenty Eleven,iNove,simleDark�ȱ�׼�����¾���������������jquery���ص�������Ҳ���Թ���������������

= 2.1.1 =
�Ż���js��css����(ѹ��).
��ӷ����������԰�(zh_TW)����л [��.����](http://6ala.com "{ ����֮�� }") @  { ����֮�� } �ṩ��
�����˲���Ķ��ڲ���׼��WordPress����ļ�����
���ƣ��޸���Ӧ���ú�rewrite�����Զ�����


= 2.1.0 =
�Ż���js��css����(Ĭ������� : load css and js  singular only ,load js only there is download shortcode)


= 2.0.9 =
fixed the bug in download_file (Check headers sent JUST before sending file,not check in the start while is not going to send file )

= 2.0.8 =
fixed the bug that adding unnecessary slash to a remotefile in function download_file 

= 2.0.7 =
* use home_url() instead of site_url() in hacklogdm class.

= 2.0.6 =
* fixed the bug (can not find function hacklogdm::favorite_actions)in version 2.0.5 
* fixed the bug when the admin bar is on,it will take the place of the tabs on top of page download-upload-or-add.php,And now this will never happen.

= 2.0.5 =
added more file extension css rules and ignored .htaccess file in filetree view.
optimized the code.

= 2.0.4 =
added jquery fileTree capability

= 2.0.3 =
fixed the bug when use filename as download param there is double / in URL (unencoded)
use WP's site_url() function instead of use . to join strings.

= 2.0.2 =
*fixed three little bug and a translation error.

= 2.0.1 =
*add direct access check to avoid path exposed for security reasons.

= 2.0.0 =
1. version changed to 2.0.0,I did a lot of work to reform the code,to make it more readable.
2. fixed some litle bug
3. beautified the upload page in WordPress thickbox.
4. beautified the page navi.
5. fixed a bug that when search in the thickbox upload page,WP will stop us and say:"You don't have permission to do this".


= 1.5.6 =

2011/09/17
1. �汾�Ÿ���Ϊ 1.5.6
2. �޸����ļ�����ʽΪ��������ʱ����ػ�ȡ�ļ���ʧ�ܵ�BUG
3. ���ӣ������ļ�����ʽ����ʱ����URL������


= 1.5.5 =

2011/09/16
1. �汾�š�1.5.5
2. �������ش��ļ���256M���£�֧��
3. �����ļ���СΪ0�жϡ��ļ���ȡ�����ж�

= 1.5.4 =

2011/09/07
1. �汾�š�1.5.4
2. �������ϴ�ʧ����ɲ������������������ID�Ķ̴����bug���磺
	`[download id="1,2,3"]`


= 1.5.3 =

2011/09/05
1. �汾�� 1.5.3
2. �������ϴ��ļ�ʱ�����ƶ��ļ�����ӦĿ¼ʧ�ܣ��ļ����ݻ����ر���ӽ����ݿ��BUG
3. ����popup��ʾ��ʽ
4. �����Զ���CSS����

= 1.5.2 =

2011/04/26
1. �汾�� 1.5.2
2. ����thickbox���������������ʾ����
3. thickbox���ڶ�������һ��Ӱ�ť����������
4. RSS��Ƕ�������ļ���ʾ�����ļ�����ҳ���URL
5. ����ѡ�����Ӷ�muվ���жϣ�����·�����Ӷ�ת�Ʋ��ͺ��ַ�Ƿ���ڵ��жϣ���������������ΪĬ��·��(/path-to/wp-content/files)
6. ����7z��ʽ�ļ���icon
7. �����ļ�ʱ���������ļ������ļ�����ɾ�������� **.bak** ��׺����������֮ .(�� foo.rar.bak )

= 1.5.1 =

2011/03/15
1. �汾���޸�Ϊ1.5.1
2. ����Mysql4.0���޷�������װ�����BUG
3. ����һ��С�ط����ȼ��upgrade.php�ټ��upgrade-functions.php��

= 1.5.0 =

2011/02/18
1. ����Զ���ļ����ش�����ͳ�Ƶ�BUG
2. �����汾�ŵ�1.5

2010/12/02
 1������Զ���ļ�����������ӵ�BUG
 2�������ظ��ļ����BUG
 3��Զ���ļ������޸�Ϊֱ��REDIRECT����С����������

2010/10/30
 ���ӣ��ڱ༭����ʱֱ�Ӳ�����ϴ��ļ�Ȼ��������µĹ���

2010/10/26
 1,�����ع���������ֱ�������ļ�ID�޸��ļ��Ĺ���
 2�����༭�ļ������Ӷ��ļ��Ƿ���ڵ��ж�
 3,����ѡ��ʹ�ù̶����ӡ� �󲻽���̨��һ�¡����á�-�����̶����ӡ� �޷����������ļ���BUG

2010/10/24
 1,��������ظ��ļ����
 2���޸��ϴ��ļ�ʱ��ѡ���ļ�Ҳ����һ��ID��BUG

2010/06/13
 ����IE��������������BUG 

2010/05/24
 ����������bug

2010/05/21
 �������ش���ͳ��
 ���ӷ�������ǿ��HTTP��·��飩���ܵ�����ѡ��

2010-5-18
 1���޸����������ƣ����ڲ������������ļ�����������ԭ�����������������ļ���������Ϊ������+�ļ�����md5ֵ��(�ļ����ػ�������ԭ����
 2����̨������ʧ�ļ���ʾ���ܣ��Ժ�ɫ������ʾ��
 2010-05-07:
 1,���ӷ���������
 2,�����ϴ�bug
 3,����md5У��

 2010-05-06:
����һ��ɾ��bug,(ԭ���޷�����ɾ���ļ�)
* `if(!unlink($file_path.$file)) {`  ����Ϊ��`if(!unlink($file_path.��/��.$file)) {`
* ����������ȷ���������ļ�����bug
  ԭ���޷������ϴ��ʹ��������ļ������ļ�������֮��

* ��ǿrewrite����
  ����ԭ��̶�����ģʽ���������ɶ���̶����ӡ�

* ��������Զ���ļ������ط������Ĺ���
	����Զ���ļ�������ѡ���Ƿ�Ҫ�洢�����ط�������

* �޸����ú����ò��ʱ��bug
 ԭ���ǽ��ú����ò���������ļ�ȫ����Ϊ�������ء����ǲ�������ġ�

* ȥ����widget
	 �о�����������õú��٣�ȥ���ˡ�

* ȥ����rssҳ��
	���Ҳ�������ðɡ�

* ȥ���������ģ������ҳ��
	ԭ���ģ������ҳ����һ��Ѷ�������..  

* ȥ���˷���
	������һ�����ˣ�����û���ù����ķ��๦�ܡ�
	�˹���������Ҫ���Ժ����Ӿ����ˡ�

* ������win��Ŀ¼��������bug
	ԭ���������win�������£�����Ŀ¼��·���ǲ����ˡ�
	��optionsҳ�棬stripslashes �� \ ���û�ˣ���ʱ����������ã������·������
	����֮��`str_replace(��\\��,��/',WP_CONTENT_DIR)`

* �������options������ѡ����ٵ�5��


* ���ӷ���������



http://ihacklog.com/
http://ihacklog.com/wordpress/plugins/hacklog-downloadmanager.html

											by ��Ұ�޵� 
											2011-10-10
