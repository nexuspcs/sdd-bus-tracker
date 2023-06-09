//
//  LEANMenuViewController.h
//  GoNativeIOS
//
//  Created by Weiyin He on 2/7/14.
//  Copyright (c) 2014 St. Luke's Grammar School Bus Tracker. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "REFrostedViewController.h"
#import "LEANLoginManager.h"

@interface LEANMenuViewController : UITableViewController
- (void)updateMenuWithStatus:(NSString*)status;
- (void)parseProfilePickerJSON:(NSString*)json;
@end
