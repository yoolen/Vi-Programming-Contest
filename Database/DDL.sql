create table state(
	state_PK		int					auto_increment primary key,
    state			varchar(255)		not null
);

create table affiliation(
	aff_PK			int					auto_increment primary key,
    affname			varchar(255)		not null,
    email			varchar(255)		not null,
    phone			varchar(20),
    street1			varchar(255)		not null,
    street2			varchar(255),
    city			varchar(255)		not null,
    state_FK		int					not null,
    zip				varchar(20)			not null,
    foreign key(state_FK) references state(state_PK)
);

create table usr( -- used in authenticating users
	usr_PK			int					auto_increment primary key,
    usrname			varchar(255)		not null,
    fname			varchar(255)		not null,
    lname			varchar(255)		not null,
    joindate		datetime			not null,
    aff_FK			int					not null,	-- affiliation
    email			varchar(255)		not null,	-- at least one point of contact needs to be provided
    phone			varchar(20),					-- phone should be optional
    street1			varchar(255)		not null,
    street2			varchar(255),
    city			varchar(255)		not null,
    state_FK		int					not null,
    zip				varchar(20)			not null,
    passhash		varchar(255)		not null,	-- only hashed password will be stored
    creds			int					not null,	-- 0 admin, 1 judge, 2 grader, 3 contestant
    foreign key(aff_FK) references affiliation(aff_PK),
    foreign key(state_FK) references state(state_PK)
);

create table team(
	team_PK			int					auto_increment primary key,
    aff_FK			int					not null,
    contact_FK		int					not null,
    coach_FK		int					not null,
    foreign key(aff_FK) references affiliation(aff_PK),
    foreign key(contact_FK) references usr(usr_PK),
    foreign key(coach_FK) references usr(usr_PK)
);

create table teammember(
	tm_PK			int					auto_increment primary key,
	team_FK			int					not null,
    usr_FK			int					not null,
    foreign key(team_FK) references team(team_PK),
    foreign key(usr_FK) references usr(usr_PK)
);

create table contest(
	contest_PK		int					auto_increment primary key,
    starttime		datetime			not null,
    duration		time				not null,
    creator_FK		int					not null,
    foreign key(creator_FK) references usr(usr_PK)
);

create table contestview(
	cv_PK			int					auto_increment primary key,
    contest_FK		int					not null,
    usr_FK			int					not null,
    foreign key(contest_FK) references contest(contest_PK),
    foreign key(usr_FK) references usr(usr_PK)
);

create table question(
	question_PK		int					auto_increment primary key,
    qtext			longtext			not null,
    answer			longtext			not null
);

create table contestquestions(
	cq_PK			int					auto_increment primary key,
	contest_FK		int					not null,
    question_FK		int					not null,
    sequencenum		int					not null,
    foreign key(contest_FK) references contest(contest_PK),
    foreign key(question_FK) references question(question_PK)
);

create table questionio(
	qio_PK			int					auto_increment primary key,
    question_FK		int					not null,
    input			longtext			not null,
    output			longtext			not null,
    notes			longtext,
    foreign key(question_FK) references question(question_PK)
);

create table checkin(
	checkin_PK		int					auto_increment primary key,
    contest_FK		int					not null,
    team_FK			int					not null,
    checkedin		bool				not null default 0,
    foreign key(contest_FK) references contest(contest_PK),
    foreign key(team_FK) references team(team_PK)
);

create table submission(
	sub_PK			int					auto_increment primary key,
	question_FK		int					not null,
    team_FK			int					not null,
    submission		longtext,
    subtime			datetime			not null,
    foreign key(question_FK) references question(question_PK),
    foreign key(team_FK) references team(team_PK)
);

create table subgrade(
	subgrade_PK		int					auto_increment primary key,
    sub_FK			int					not null,
    team_FK			int					not null,
    grade			bool				default 0,
    foreign key(sub_FK) references submission(sub_PK),
    foreign key(team_FK) references team(team_PK)
);

create table teamscore(
	teamscore_PK	int					auto_increment primary key,
    contest_FK		int					not null,
    team_FK			int					not null,
    score			int					default 0,
    foreign key(contest_FK) references contest(contest_PK),
    foreign key(team_FK) references team(team_PK)
);