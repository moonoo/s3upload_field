<?php

	require_once(EXTENSIONS .'/s3upload_field/lib/S3.php');

	Class extension_s3upload_field extends Extension {

		public function about() {
			return array(
				'name'			=> 'Field: Amazon S3 File Upload',
				'version'		=> '.5',
				'release-date'	=> '2010-06-24',
				'author'		=> array(
					'name'			=> 'Andrew Shooner and Brian Zerangue',
					'website'		=> 'http://andrewshooner.com',
					'email'			=> 'ashooner@gmail.com'
				),
				'description'	=> 'Upload files to Amazon S3. Based on Brian Zerangue\'s version, based on Michael E\'s upload field.'
			);
		}
		
		public function getSubscribedDelegates(){
					return array(
								array(
									'page' => '/system/preferences/',
									'delegate' => 'AddCustomPreferenceFieldsets',
									'callback' => 'appendPreferences'
								)
					);
		}
		
		public function appendPreferences($context){
					$group = new XMLElement('fieldset');
					$group->setAttribute('class', 'settings');
					$group->appendChild(new XMLElement('legend', 'Amazon S3 Security Credentials'));

					$label = Widget::Label('Access Key ID');
					$label->appendChild(Widget::Input('settings[s3upload_field][access-key-id]', General::Sanitize($this->getAmazonS3AccessKeyId())));		
					$group->appendChild($label);
					
					$label = Widget::Label('Secret Access Key');
					$label->appendChild(Widget::Input('settings[s3upload_field][secret-access-key]', General::Sanitize($this->getAmazonS3SecretAccessKey())));		
					$group->appendChild($label);

					$group->appendChild(new XMLElement('p', 'Get a Access Key ID and Secret Access Key from the <a href="http://aws.amazon.com">Amazon Web Services site</a>.', array('class' => 'help')));

					$context['wrapper']->appendChild($group);

				}

		public function uninstall() {
			$this->_Parent->Database->query("DROP TABLE `tbl_fields_s3upload`");
		}

		public function install() {
			return $this->_Parent->Database->query("CREATE TABLE `tbl_fields_s3upload` (
				`id` int(11) unsigned NOT NULL auto_increment,
				`field_id` int(11) unsigned NOT NULL,
				`bucket` varchar(255) NOT NULL,
				`validator` varchar(50),
				PRIMARY KEY (`id`),
				KEY `field_id` (`field_id`))"
			);
		}
		
		public function getAmazonS3AccessKeyId(){
					if(class_exists('ConfigurationAccessor'))
						return ConfigurationAccessor::get('access-key-id', 's3upload_field');

					return $this->_Parent->Configuration->get('access-key-id', 's3upload_field');
				}
				
		public function getAmazonS3SecretAccessKey(){
					if(class_exists('ConfigurationAccessor'))
						return ConfigurationAccessor::get('secret-access-key', 's3upload_field');

					return $this->_Parent->Configuration->get('secret-access-key', 's3upload_field');
				}
				

	}
